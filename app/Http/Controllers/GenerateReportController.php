<?php

namespace App\Http\Controllers;

use App\Models\TransactionModel;
use App\Models\ExpenseModel; // Add this model for expenses
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class GenerateReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            $date = $request->date
                ? Carbon::createFromFormat('Y-m-d', $request->date, 'Asia/Manila')->startOfDay()
                : Carbon::today('Asia/Manila');
        } catch (\Exception $e) {
            $date = Carbon::today('Asia/Manila');
        }

        $displayDate = $date->format('d/m/Y');

        $transactions = TransactionModel::select(
            'tbl_transactions.*',
            'tbl_customers.FirstName',
            'tbl_customers.LastName',
            DB::raw('CASE WHEN tbl_transactions.total_amount = 0 THEN tbl_transactions.subtotal ELSE tbl_transactions.total_amount END as final_amount')
        )
            ->join('tbl_customers', 'tbl_transactions.CustomerID', '=', 'tbl_customers.CustomerID')
            ->whereDate('tbl_transactions.created_at', $date)
            ->orderBy('tbl_transactions.created_at', 'desc')
            ->paginate(15);

        // Get totals for different payment types
        $totalCashSales = TransactionModel::where('payment_type', 'cash')
            ->whereDate('created_at', $date)
            ->sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END'));

        $totalDebitSales = TransactionModel::where('payment_type', 'debit')
            ->whereDate('created_at', $date)
            ->sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END'));

        $totalOnlineSales = TransactionModel::where('payment_type', 'online')
            ->whereDate('created_at', $date)
            ->sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END'));

        // Get expenses for the day
        $totalExpenses = ExpenseModel::whereDate('created_at', $date)->sum('e_amount');

        return view('admin.pages.reports.generate-report',
            compact('transactions', 'totalCashSales', 'totalDebitSales', 'totalExpenses','totalOnlineSales', 'displayDate'));
    }

    public function export($date)
{
    try {
        $date = Carbon::createFromFormat('Y-m-d', $date, 'Asia/Manila')->startOfDay();

        if (!$date) {
            throw new \Exception('Invalid date');
        }

        // Get transactions
        $transactions = TransactionModel::select(
            'tbl_transactions.*',
            'tbl_customers.FirstName',
            'tbl_customers.LastName',
            DB::raw('CASE WHEN tbl_transactions.total_amount = 0 THEN tbl_transactions.subtotal ELSE tbl_transactions.total_amount END as final_amount')
        )
            ->join('tbl_customers', 'tbl_transactions.CustomerID', '=', 'tbl_customers.CustomerID')
            ->whereDate('tbl_transactions.created_at', $date)
            ->orderBy('tbl_transactions.created_at', 'desc')
            ->get();

        // Get expenses
        $expenses = ExpenseModel::whereDate('created_at', $date)
            ->orderBy('created_at', 'desc')
            ->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('Glency\'s System')
            ->setLastModifiedBy('Glency\'s System')
            ->setTitle('Daily Sales Report')
            ->setSubject('Sales Report for ' . $date->format('F d, Y'));

        // Style the main header
        $sheet->setCellValue('A1', 'GLENCY\'S DRESSED CHICKEN AND TRADING');
        $sheet->mergeCells('A1:H1');
        $sheet->setCellValue('A2', 'DAILY SALES REPORT');
        $sheet->mergeCells('A2:H2');
        $sheet->setCellValue('A3', 'DATE: ' . $date->format('F d, Y'));
        $sheet->mergeCells('A3:H3');

        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1F4E78'],
            ],
        ];

        // Apply different sizes to headers
        $sheet->getStyle('A1')->getFont()->setSize(16);
        $sheet->getStyle('A2')->getFont()->setSize(14);
        $sheet->getStyle('A3')->getFont()->setSize(12);

        $sheet->getStyle('A1:H1')->applyFromArray($headerStyle);
        $sheet->getStyle('A2:H2')->applyFromArray($headerStyle);
        $sheet->getStyle('A3:H3')->applyFromArray($headerStyle);

        // Style the Sales Transaction section
        $sheet->setCellValue('A4', 'SALES TRANSACTIONS');
        $sheet->mergeCells('A4:H4');

        $sectionHeaderStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '2E75B6'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];
        $sheet->getStyle('A4:H4')->applyFromArray($sectionHeaderStyle);
        $sheet->getRowDimension(4)->setRowHeight(25);

        // Style table headers
        $tableHeaderStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => '305496'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
        ];

        // Sales headers
        $headers = [
            'A5' => 'CUSTOMER\'S NAME',
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

        $sheet->getStyle('A5:H5')->applyFromArray($tableHeaderStyle);
        $sheet->getRowDimension(5)->setRowHeight(20);

        // Add transactions
        $row = 6;
        $totalCash = 0;
        $totalDebit = 0;
        $totalOnline = 0;
        $totalCredit = 0;

        foreach ($transactions as $transaction) {
            $customerName = $transaction->FirstName . ' ' . $transaction->LastName;

            $sheet->setCellValueExplicit('A' . $row, $customerName, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);
            $sheet->setCellValueExplicit('B' . $row, $transaction->receipt_id, \PhpOffice\PhpSpreadsheet\Cell\DataType::TYPE_STRING);

            // Reset payment columns
            $sheet->setCellValue('C' . $row, '');
            $sheet->setCellValue('D' . $row, '');
            $sheet->setCellValue('E' . $row, '');
            $sheet->setCellValue('F' . $row, '');

            switch($transaction->payment_type) {
                case 'cash':
                    $sheet->setCellValue('C' . $row, $transaction->final_amount);
                    $totalCash += $transaction->final_amount;
                    break;
                case 'debit':
                    $sheet->setCellValue('D' . $row, $transaction->final_amount);
                    $totalDebit += $transaction->final_amount;
                    break;
                case 'online':
                    $sheet->setCellValue('E' . $row, $transaction->final_amount);
                    $totalOnline += $transaction->final_amount;
                    break;
                case 'credit':
                    $sheet->setCellValue('F' . $row, $transaction->final_amount);
                    $totalCredit += $transaction->final_amount;
                    break;
            }

            $sheet->setCellValue('G' . $row, $transaction->remarks ?? '');
            $sheet->setCellValue('H' . $row, Carbon::parse($transaction->created_at)->format('M d, Y'));

            foreach (['C', 'D', 'E', 'F'] as $column) {
                $sheet->getStyle($column . $row)->getNumberFormat()->setFormatCode('#,##0.00');
            }

            $row++;
        }

        // Add zebra striping to transaction rows
        $dataEndRow = $row - 1;
        for ($i = 6; $i <= $dataEndRow; $i += 2) {
            $sheet->getStyle('A'.$i.':H'.$i)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('F2F6FC');
        }

        // Add borders to the data
        $sheet->getStyle('A6:H'.$dataEndRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'BFBFBF'],
                ],
            ],
        ]);

        // Add a gap before expenses section
        $row += 2;

        // Expenses Section
        $sheet->setCellValue('A' . $row, 'EXPENSES');
        $sheet->mergeCells('A'.$row.':E'.$row);
        $sheet->getStyle('A'.$row.':E'.$row)->applyFromArray($sectionHeaderStyle);
        $row++;

        // Expenses headers
        $expenseHeaders = [
            'A' . $row => 'DESCRIPTION',
            'B' . $row => 'AMOUNT',
            'C' . $row => 'WITHDRAWN BY',
            'D' . $row => 'RECEIVED BY',
            'E' . $row => 'DATE'
        ];
        foreach ($expenseHeaders as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }
        $sheet->getStyle('A'.$row.':E'.$row)->applyFromArray($tableHeaderStyle);
        $expenseStartRow = $row + 1;

        $row++;
        $totalExpenses = 0;

        foreach ($expenses as $expense) {
            $sheet->setCellValue('A' . $row, $expense->e_description);
            $sheet->setCellValue('B' . $row, $expense->e_amount);
            $sheet->setCellValue('C' . $row, $expense->e_withdraw_by ?? '');
            $sheet->setCellValue('D' . $row, $expense->e_recieve_by ?? '');
            $sheet->setCellValue('E' . $row, Carbon::parse($expense->created_at)->format('M d, Y'));

            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

            $totalExpenses += $expense->e_amount;
            $row++;
        }

        // Add zebra striping and borders to expenses
        $expenseEndRow = $row - 1;
        for ($i = $expenseStartRow; $i <= $expenseEndRow; $i += 2) {
            $sheet->getStyle('A'.$i.':E'.$i)->getFill()
                ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
                ->getStartColor()->setRGB('F2F6FC');
        }

        $sheet->getStyle('A'.$expenseStartRow.':E'.$expenseEndRow)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'BFBFBF'],
                ],
            ],
        ]);

        // Summary Section
        $row += 2;
        $sheet->setCellValue('A' . $row, 'SUMMARY');
        $sheet->mergeCells('A'.$row.':B'.$row);
        $sheet->getStyle('A'.$row.':B'.$row)->applyFromArray($sectionHeaderStyle);

        $summaryStyle = [
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E9EFF7'],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT,
            ],
        ];

        // Sales Summary
        $row++;
        $summaryStartRow = $row;
        $sheet->setCellValue('A' . $row, 'Total Cash Sales:');
        $sheet->setCellValue('B' . $row, $totalCash);

        $row++;
        $sheet->setCellValue('A' . $row, 'Total Balance Payment:');
        $sheet->setCellValue('B' . $row, $totalDebit);

        $row++;
        $sheet->setCellValue('A' . $row, 'Total Online Payment:');
        $sheet->setCellValue('B' . $row, $totalOnline);

        $row++;
        $sheet->setCellValue('A' . $row, 'Total Credit/Charge:');
        $sheet->setCellValue('B' . $row, $totalCredit);

        $row++;
        $totalSales = $totalCash + $totalDebit + $totalOnline + $totalCredit;
        $sheet->setCellValue('A' . $row, 'Total Sales:');
        $sheet->setCellValue('B' . $row, $totalSales);

        $row++;
        $sheet->setCellValue('A' . $row, 'Total Expenses:');
        $sheet->setCellValue('B' . $row, $totalExpenses);

        $row++;
        $sheet->setCellValue('A' . $row, 'Net Income:');
        $sheet->setCellValue('B' . $row, $totalSales - $totalExpenses);

        // Apply summary styles
        $sheet->getStyle('A'.$summaryStartRow.':B'.$row)->applyFromArray($summaryStyle);

        // Format all summary amounts
        for ($i = $summaryStartRow; $i <= $row; $i++) {
            $sheet->getStyle('B' . $i)->getNumberFormat()->setFormatCode('#,##0.00');
        }

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(35);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(15);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(15);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(25);
        $sheet->getColumnDimension('H')->setWidth(15);

        // Set headers and output
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="sales_report_' . $date->format('Y-m-d') . '.xlsx"');
        header('Cache-Control: no-cache');
        header('Pragma: no-cache');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit();

    } catch (\Exception $e) {
        \Log::error('Export error: ' . $e->getMessage());
        return redirect()->back()->with('error', 'Failed to generate report. Please try again.');
    }
}
}