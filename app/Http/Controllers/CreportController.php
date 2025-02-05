<?php

namespace App\Http\Controllers;

use App\Models\TransactionItemModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;

class CreportController extends Controller
{
    public function index(Request $request)
    {
        // Get the authenticated user's ID
        $userId = Auth::id();

        // Get filter parameters with proper date handling
        $startDate = $request->input('start_date')
            ? Carbon::parse($request->input('start_date'))->startOfDay()
            : Carbon::now()->startOfMonth();
        $endDate = $request->input('end_date')
            ? Carbon::parse($request->input('end_date'))->endOfDay()
            : Carbon::now()->endOfDay();
        $paymentType = $request->input('payment_type');

        // Format dates for the view
        $startDateFormatted = $startDate->format('Y-m-d');
        $endDateFormatted = $endDate->format('Y-m-d');

        // Rest of your query logic...
        $query = TransactionItemModel::with(['transaction.customer', 'product'])
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($paymentType) {
            $query->whereHas('transaction', function ($q) use ($paymentType) {
                $q->where('payment_type', $paymentType);
            });
        }

        $transactionItems = $query->get();

        // Calculate summaries...
        $totalItems = $transactionItems->count();
        $totalKilos = $transactionItems->sum('kilos');
        $totalSales = $transactionItems->sum('total');
        $averagePricePerKilo = $totalKilos > 0 ? $totalSales / $totalKilos : 0;

        return view('cashier.pages.report.index', compact(
            'transactionItems',
            'totalItems',
            'totalKilos',
            'totalSales',
            'averagePricePerKilo',
            'startDateFormatted',
            'endDateFormatted',
            'paymentType'
        ));
    }

    public function export(Request $request)
    {
        try {
            // Get filter parameters and user ID
            $userId = Auth::id();
            $date = $request->input('date', Carbon::now());

            // Query builder
            $query = TransactionItemModel::with(['transaction.customer', 'product'])
                ->where('user_id', $userId)
                ->whereDate('created_at', $date);

            $transactions = $query->get();

            // Calculate totals by payment type
            $totalCashSales = $transactions->where('transaction.payment_type', 'cash')
                ->sum('total');
            $totalBalancePayment = $transactions->where('transaction.payment_type', 'debit')
                ->sum('total');
            $totalOnlinePayment = $transactions->where('transaction.payment_type', 'online')
                ->sum('total');
            $totalCreditCharge = $transactions->where('transaction.payment_type', 'credit')
                ->sum('total');
            $totalSales = $totalCashSales + $totalBalancePayment + $totalOnlinePayment + $totalCreditCharge;

            // Create new Spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set column widths
            $sheet->getColumnDimension('A')->setWidth(30);
            $sheet->getColumnDimension('B')->setWidth(15);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(15);
            $sheet->getColumnDimension('F')->setWidth(15);
            $sheet->getColumnDimension('G')->setWidth(20);
            $sheet->getColumnDimension('H')->setWidth(15);

            // Header Styles
            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1F4E78'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
            ];

            // Title Section
            $sheet->mergeCells('A1:H1');
            $sheet->setCellValue('A1', "GLENCY'S DRESSED CHICKEN AND TRADING");
            $sheet->mergeCells('A2:H2');
            $sheet->setCellValue('A2', 'DAILY SALES REPORT');
            $sheet->mergeCells('A3:H3');
            $sheet->setCellValue('A3', 'DATE: ' . Carbon::parse($date)->format('F d, Y'));

            // Apply header styles
            $sheet->getStyle('A1:H3')->applyFromArray($headerStyle);
            $sheet->getStyle('A1')->getFont()->setSize(14);
            $sheet->getStyle('A2')->getFont()->setSize(12);
            $sheet->getStyle('A3')->getFont()->setSize(11);

            // Sales Transactions Header
            $sheet->mergeCells('A4:H4');
            $sheet->setCellValue('A4', 'SALES TRANSACTIONS');
            $sheet->getStyle('A4:H4')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ]);

            // Column Headers
            $headers = [
                'A5' => "CUSTOMER'S NAME",
                'B5' => 'RECEIPT #',
                'C5' => 'CASH SALES',
                'D5' => 'BAL. PAYMENT',
                'E5' => 'ONLINE',
                'F5' => 'CREDIT/CHARGE',
                'G5' => 'REMARKS',
                'H5' => 'DATE'
            ];

            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }

            // Apply header row styles
            $sheet->getStyle('A5:H5')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '305496'],
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ],
            ]);

            // Data Rows
            $row = 6;
            foreach ($transactions as $transaction) {
                $sheet->setCellValue('A' . $row, $transaction->transaction->customer->FirstName . ' ' .
                    $transaction->transaction->customer->LastName);
                $sheet->setCellValue('B' . $row, $transaction->transaction->receipt_id);

                // Set amount in appropriate column based on payment type
                switch ($transaction->transaction->payment_type) {
                    case 'cash':
                        $sheet->setCellValue('C' . $row, $transaction->total);
                        break;
                    case 'debit':
                        $sheet->setCellValue('D' . $row, $transaction->total);
                        break;
                    case 'online':
                        $sheet->setCellValue('E' . $row, $transaction->total);
                        break;
                    case 'credit':
                        $sheet->setCellValue('F' . $row, $transaction->total);
                        break;
                }

                $sheet->setCellValue('G' . $row, $transaction->remarks ?? '');
                $sheet->setCellValue('H' . $row, Carbon::parse($transaction->created_at)->format('M d, Y'));

                // Apply number format to amount columns
                $sheet->getStyle('C' . $row . ':F' . $row)->getNumberFormat()
                    ->setFormatCode('#,##0.00');

                $row++;
            }

            // Add Summary Section
            $row += 2;
            $sheet->mergeCells('A' . $row . ':B' . $row);
            $sheet->setCellValue('A' . $row, 'SUMMARY');
            $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ]);

            // Summary rows
            $row++;
            $summaryStyle = [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
            ];

            $summaryRows = [
                'Total Cash Sales:' => $totalCashSales,
                'Total Balance Payment:' => $totalBalancePayment,
                'Total Online Payment:' => $totalOnlinePayment,
                'Total Credit/Charge:' => $totalCreditCharge,
            ];

            foreach ($summaryRows as $label => $value) {
                $sheet->setCellValue('A' . $row, $label);
                $sheet->setCellValue('B' . $row, $value);
                $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray($summaryStyle);
                $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
                $row++;
            }

            // Total Sales (with yellow background)
            $sheet->setCellValue('A' . $row, 'Total Sales:');
            $sheet->setCellValue('B' . $row, $totalSales);
            $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFF00'],
                ],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
            ]);
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

            $currentUser = Auth::user();

            $generatorName = $currentUser->name ?? 'System User';

            // Add Generated By section
            $row += 2;
            $sheet->setCellValue('A' . $row, 'Generated by: ' . $generatorName);
            $sheet->mergeCells('A' . $row . ':C' . $row);
            $sheet->getStyle('A' . $row)->applyFromArray([
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT],
            ]);

            // Add generation timestamp
            $row++;
            $sheet->setCellValue('A' . $row, 'Generated on: ' . Carbon::now()->format('M d, Y H:i:s'));
            $sheet->mergeCells('A' . $row . ':C' . $row);
            $sheet->getStyle('A' . $row)->applyFromArray([
                'font' => ['italic' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT],
            ]);

            // Set headers for download
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            $lastName = $currentUser->name ?? 'Unknown';  // Changed to match your DB convention (LastName)
            $reportDate = Carbon::parse($date)->format('Y-m-d');
            $timestamp = Carbon::now()->format('H-i-s');
            header('Content-Disposition: attachment;filename="daily_sales_report_' . $lastName . '_' . $reportDate . '_' . $timestamp . '.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit();

        } catch (\Exception $e) {
            \Log::error('Export error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to generate report. Please try again.');
        }
    }
}