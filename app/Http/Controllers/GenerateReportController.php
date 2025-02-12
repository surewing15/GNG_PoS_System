<?php

namespace App\Http\Controllers;

use App\Models\TransactionModel;
use App\Models\TransactionItemModel;
use App\Models\ExpenseModel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class GenerateReportController extends Controller
{
    public function index(Request $request)
    {
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

        // Query builder with date range - showing all transactions
        $query = TransactionItemModel::with([
            'transaction' => function ($query) {
                $query->select(
                    'transaction_id',
                    'credit_charge',
                    'payment_type',
                    'receipt_id',
                    'CustomerID',
                    'created_at',
                    'total_amount',
                    'user_id'
                );
            },
            'transaction.customer',
            'transaction.user',
            'product'
        ])
            ->whereHas('transaction', function ($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            });

        if ($paymentType) {
            $query->whereHas('transaction', function ($q) use ($paymentType) {
                $q->where('payment_type', $paymentType);
            });
        }

        $transactionItems = $query->get();

        // Calculate summaries
        $totalItems = $transactionItems->count();
        $totalKilos = $transactionItems->sum('kilos');
        $totalSales = $transactionItems->sum('total');
        $averagePricePerKilo = $totalKilos > 0 ? $totalSales / $totalKilos : 0;

        // Get unique payment types for filter dropdown
        $paymentTypes = TransactionItemModel::with('transaction')
            ->whereHas('transaction')
            ->get()
            ->pluck('transaction.payment_type')
            ->unique()
            ->filter()
            ->values();

        return view('admin.pages.reports.generate-report', compact(
            'transactionItems',
            'totalItems',
            'totalKilos',
            'totalSales',
            'averagePricePerKilo',
            'startDateFormatted',
            'endDateFormatted',
            'paymentType',
            'paymentTypes'
        ));
    }

    public function export(Request $request)
    {
        try {
            // Get filter parameters and user ID
            $userId = Auth::id();

            // Get start and end dates from request
            $startDate = $request->input('start_date')
                ? Carbon::parse($request->input('start_date'))->startOfDay()
                : Carbon::now()->startOfDay();
            $endDate = $request->input('end_date')
                ? Carbon::parse($request->input('end_date'))->endOfDay()
                : Carbon::now()->endOfDay();
            $paymentType = $request->input('payment_type');

            // Query builder with date range
            $query = TransactionItemModel::with([
                'transaction' => function ($query) {
                    $query->select(
                        'transaction_id',
                        'credit_charge',
                        'payment_type',
                        'receipt_id',
                        'CustomerID',
                        'created_at',
                        'total_amount',
                        'user_id'  // Added user_id for staff information
                    );
                },
                'transaction.customer',
                'transaction.user', // Added user relationship
                'product'
            ])
                ->whereHas('transaction', function ($query) use ($startDate, $endDate) {
                    $query->whereBetween('created_at', [$startDate, $endDate]);
                });

            if ($paymentType) {
                $query->whereHas('transaction', function ($q) use ($paymentType) {
                    $q->where('payment_type', $paymentType);
                });
            }

            $transactions = $query->get();

            $balancePayments = DB::table('tbl_payment')
                ->join('tbl_customers', 'tbl_payment.customer_id', '=', 'tbl_customers.CustomerID')
                ->whereBetween('payment_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->select(
                    'tbl_customers.FirstName',
                    'tbl_customers.LastName',
                    'tbl_customers.CustomerID',
                    'tbl_payment.id as receipt_id',
                    'tbl_payment.amount',
                    'tbl_payment.payment_date'
                )
                ->get();

            // Calculate totals by payment type
            $totalCashSales = $transactions
                ->where('transaction.payment_type', 'cash')
                ->map(function ($transaction) {
                    return $transaction->transaction->credit_charge > 0
                        ? $transaction->total - $transaction->transaction->credit_charge
                        : $transaction->total;
                })
                ->sum();
            $totalBalancePayment = $balancePayments->sum('amount');
            $totalOnlinePayment = $transactions->where('transaction.payment_type', 'online')->sum('total');
            // Modified credit charge calculation to group by transaction first
            $creditTransactions = $transactions->where('transaction.payment_type', 'credit')
                ->groupBy('transaction.transaction_id')
                ->map(function ($items) {
                    $transaction = $items->first()->transaction;
                    return $transaction->credit_charge ?? $items->sum('total');
                });
            $totalCreditCharge = $transactions->filter(function ($transaction) {
                return $transaction->transaction->payment_type === 'credit' ||
                    ($transaction->transaction->credit_charge && $transaction->transaction->credit_charge > 0);
            })->groupBy('transaction.transaction_id')
                ->map(function ($group) {
                    $firstTransaction = $group->first()->transaction;
                    return $firstTransaction->credit_charge ?? $group->sum('total');
                })->sum();


            // Calculate total sales after regrouping credit charges
            $totalSales = $totalCashSales + $totalBalancePayment + $totalOnlinePayment;
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

            // Show date range in title
            if ($startDate->isSameDay($endDate)) {
                $dateDisplay = 'DATE: ' . $startDate->format('F d, Y');
            } else {
                $dateDisplay = 'DATE: ' . $startDate->format('F d, Y') . ' to ' . $endDate->format('F d, Y');
            }
            $sheet->setCellValue('A3', $dateDisplay);

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
            // Data Rows
            $row = 6;
            foreach ($transactions as $transaction) {
                $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);

                $sheet->setCellValue('A' . $row, $transaction->transaction->customer->FirstName . ' ' .
                    $transaction->transaction->customer->LastName);
                $sheet->setCellValue('B' . $row, $transaction->transaction->receipt_id);

                // Check payment type first
                switch ($transaction->transaction->payment_type) {
                    case 'cash':
                        // If there's a credit charge, reduce the cash sale amount by the credit charge
                        $cashAmount = $transaction->transaction->credit_charge > 0
                            ? $transaction->total - $transaction->transaction->credit_charge
                            : $transaction->total;

                        $sheet->setCellValue('C' . $row, $cashAmount);

                        // Set credit charge if present
                        if ($transaction->transaction->credit_charge > 0) {
                            $sheet->setCellValue('F' . $row, $transaction->transaction->credit_charge);
                        }
                        break;
                    case 'online':
                        $sheet->setCellValue('E' . $row, $transaction->total);
                        // Check if online transaction has credit charge
                        if ($transaction->transaction->credit_charge > 0) {
                            $sheet->setCellValue('F' . $row, $transaction->transaction->credit_charge);
                        }
                        break;
                    case 'debit':
                        // For debit, only show in CREDIT/CHARGE column
                        $sheet->setCellValue('F' . $row, $transaction->total);
                        break;
                    case 'credit':
                        $sheet->setCellValue('F' . $row, $transaction->transaction->credit_charge ?? $transaction->total);
                        break;
                }

                $sheet->setCellValue('G' . $row, $transaction->remarks ?? '');
                $sheet->setCellValue('H' . $row, Carbon::parse($transaction->created_at)->format('M d, Y'));

                $sheet->getStyle('C' . $row . ':F' . $row)->getNumberFormat()
                    ->setFormatCode('#,##0.00');

                $row++;
            }

            foreach ($balancePayments as $payment) {
                $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);

                $sheet->setCellValue('A' . $row, $payment->FirstName . ' ' . $payment->LastName);
                $sheet->setCellValue('B' . $row, 'PMT-' . str_pad($payment->receipt_id, 5, '0', STR_PAD_LEFT));
                $sheet->setCellValue('D' . $row, $payment->amount);
                $sheet->setCellValue('G' . $row, 'Balance Payment');
                $sheet->setCellValue('H' . $row, Carbon::parse($payment->payment_date)->format('M d, Y'));

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
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ],
            ];

            $summaryRows = [
                ['label' => 'Total Cash Sales:', 'value' => $totalCashSales, 'isCredit' => false],
                ['label' => 'Total Balance Payment:', 'value' => $totalBalancePayment, 'isCredit' => false],
                ['label' => 'Total Online Payment:', 'value' => $totalOnlinePayment, 'isCredit' => false],
                ['label' => 'Total Credit/Charge:', 'value' => $totalCreditCharge, 'isCredit' => true],
            ];

            foreach ($summaryRows as $summaryRow) {
                $sheet->setCellValue('A' . $row, $summaryRow['label']);
                $sheet->setCellValue('B' . $row, $summaryRow['value']);

                if ($summaryRow['isCredit']) {
                    // Special styling for credit/charge amount with red font
                    $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'FF0000'], // Red color
                        ],
                        'alignment' => [
                            'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT
                        ],
                        'borders' => [
                            'allBorders' => [
                                'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                            ],
                        ],
                    ]);
                } else {
                    // Regular summary styling for non-credit rows
                    $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray($summaryStyle);
                }

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




            // Add Generated By section
            $currentUser = Auth::user();
            $generatorName = $currentUser->name ?? 'System User';

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

            // Create filename with date range
            if ($startDate->isSameDay($endDate)) {
                $dateForFilename = $startDate->format('Y-m-d');
            } else {
                $dateForFilename = $startDate->format('Y-m-d') . '_to_' .
                    $endDate->format('Y-m-d');
            }
            $timestamp = Carbon::now()->format('H-i-s');

            $filename = "daily_sales_report_{$generatorName}_{$dateForFilename}_{$timestamp}.xlsx";

            // Buffer the output
            ob_start();
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            $content = ob_get_contents();
            ob_end_clean();

            return response($content)
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'max-age=0');

        } catch (\Exception $e) {
            \Log::error('Export error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Failed to generate report. Please try again.');
        }
    }
}