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
use App\Models\User;

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


            // Get start and end dates from request
            $startDate = $request->input('start_date')
                ? Carbon::parse($request->input('start_date'))->startOfDay()
                : Carbon::now()->startOfDay();
            $endDate = $request->input('end_date')
                ? Carbon::parse($request->input('end_date'))->endOfDay()
                : Carbon::now()->endOfDay();
            $paymentType = $request->input('payment_type');

            // Query builder with date range - removed user_id filter
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
                        'amount_paid',
                        'reference_number',
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

            $transactions = $query->get();

            // Group transactions by user
            $userTransactions = $transactions->groupBy('transaction.user_id');

            // Get all expenses without user filter
            $expenses = ExpenseModel::whereBetween('created_at', [$startDate, $endDate])->get();
            $userExpenses = $expenses->groupBy('user_id');

            $totalExpenses = $expenses->sum('e_amount');
            $totalReturns = $expenses->sum('e_return_amount');
            $netExpenses = $totalExpenses - $totalReturns;

            // Get balance payments with user information
            $balancePayments = DB::table('tbl_payment')
                ->join('tbl_customers', 'tbl_payment.customer_id', '=', 'tbl_customers.CustomerID')
                ->join('users', 'tbl_payment.user_id', '=', 'users.id')
                ->whereBetween('payment_date', [$startDate->format('Y-m-d'), $endDate->format('Y-m-d')])
                ->select(
                    'tbl_customers.FirstName',
                    'tbl_customers.LastName',
                    'tbl_customers.CustomerID',
                    'tbl_payment.id as receipt_id',
                    'tbl_payment.amount',
                    'tbl_payment.payment_date',
                    'users.name as user_name',
                    'users.id as user_id'
                )
                ->get();

            // Group balance payments by user
            $userBalancePayments = $balancePayments->groupBy('user_id');

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
            $sheet->getColumnDimension('I')->setWidth(20);

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
            $sheet->mergeCells('A1:I1');
            $sheet->setCellValue('A1', "GLENCY'S DRESSED CHICKEN AND TRADING");
            $sheet->mergeCells('A2:I2');
            $sheet->setCellValue('A2', 'DAILY SALES REPORT (ADMIN)');
            $sheet->mergeCells('A3:I3');

            // Show date range in title
            if ($startDate->isSameDay($endDate)) {
                $dateDisplay = 'DATE: ' . $startDate->format('F d, Y');
            } else {
                $dateDisplay = 'DATE: ' . $startDate->format('F d, Y') . ' to ' . $endDate->format('F d, Y');
            }
            $sheet->setCellValue('A3', $dateDisplay);

            // Apply header styles
            $sheet->getStyle('A1:I3')->applyFromArray($headerStyle);
            $sheet->getStyle('A1')->getFont()->setSize(14);
            $sheet->getStyle('A2')->getFont()->setSize(12);
            $sheet->getStyle('A3')->getFont()->setSize(11);

            $row = 4;
            $grandTotalCashSales = 0;
            $grandTotalBalancePayment = 0;
            $grandTotalOnlinePayment = 0;
            $grandTotalCreditCharge = 0;

            // Process each user's data
            foreach ($userTransactions as $userId => $userTxns) {
                $user = $userTxns->first()->transaction->user;

                // User Section Header
                $row++;
                $sheet->mergeCells("A{$row}:I{$row}");
                $sheet->setCellValue("A{$row}", "USER: " . ($user ? $user->name : 'Unknown User'));
                $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4472C4'],
                    ],
                ]);

                // Column Headers
                $row++;
                $headers = [
                    'A' => "CUSTOMER'S NAME",
                    'B' => 'RECEIPT #',
                    'C' => 'CASH SALES',
                    'D' => 'BAL. PAYMENT',
                    'E' => 'ONLINE',
                    'F' => 'CREDIT/CHARGE',
                    'G' => 'REMARKS',
                    'H' => 'DATE',
                    'I' => 'STAFF'
                ];

                foreach ($headers as $col => $value) {
                    $sheet->setCellValue($col . $row, $value);
                }

                // Style headers
                $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '305496'],
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);

                $row++;

                // Calculate user totals
                $userTotalCashSales = 0;
                $userTotalBalancePayment = 0;
                $userTotalOnlinePayment = 0;
                $userTotalCreditCharge = 0;

                // Process transactions
                foreach ($userTxns as $transaction) {
                    $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                        ],
                    ]);

                    // Set customer name and receipt number
                    $sheet->setCellValue('A' . $row, $transaction->transaction->customer->FirstName . ' ' .
                        $transaction->transaction->customer->LastName);
                    $sheet->setCellValue('B' . $row, $transaction->transaction->receipt_id);
                    $sheet->setCellValue('I' . $row, $transaction->transaction->user->name ?? 'Unknown');

                    // Process payment types
                    switch ($transaction->transaction->payment_type) {
                        case 'online':
                            $amount = $transaction->total;
                            $sheet->setCellValue('E' . $row, $amount);
                            $sheet->setCellValue('G' . $row, 'Online Payment' .
                                ($transaction->transaction->reference_number ? ' (Ref: ' . $transaction->transaction->reference_number . ')' : ''));
                            $userTotalOnlinePayment += $amount;
                            break;

                        case 'cash':
                            $cashAmount = $transaction->transaction->credit_charge > 0
                                ? $transaction->total - $transaction->transaction->credit_charge
                                : $transaction->total;
                            $sheet->setCellValue('C' . $row, $cashAmount);
                            $userTotalCashSales += $cashAmount;
                            if ($transaction->transaction->credit_charge > 0) {
                                $sheet->setCellValue('F' . $row, $transaction->transaction->credit_charge);
                                $userTotalCreditCharge += $transaction->transaction->credit_charge;
                            }
                            break;

                        case 'credit':
                            $creditAmount = $transaction->transaction->credit_charge ?? $transaction->total;
                            $sheet->setCellValue('F' . $row, $creditAmount);
                            $sheet->setCellValue('G' . $row, 'Credit Transaction');
                            $userTotalCreditCharge += $creditAmount;
                            break;

                        case 'advance_payment':
                            $advanceAmount = $transaction->transaction->amount_paid ?? $transaction->total;
                            $sheet->setCellValue('C' . $row, $advanceAmount);
                            $userTotalCashSales += $advanceAmount; // Add to cash sales total

                            // If there's a remaining balance, show it as credit
                            $remainingBalance = $transaction->total - $advanceAmount;
                            if ($remainingBalance > 0) {
                                $sheet->setCellValue('F' . $row, $remainingBalance);
                                $userTotalCreditCharge += $remainingBalance;
                            }

                            $sheet->setCellValue('G' . $row, 'Advance Payment');
                            break;
                    }

                    $sheet->setCellValue('H' . $row, Carbon::parse($transaction->created_at)->format('M d, Y'));
                    $sheet->getStyle("C{$row}:F{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                    $row++;
                }

                // Process balance payments for current user
                if (isset($userBalancePayments[$userId])) {
                    foreach ($userBalancePayments[$userId] as $payment) {
                        $sheet->getStyle("A{$row}:I{$row}")->applyFromArray([
                            'borders' => [
                                'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                            ],
                        ]);

                        $sheet->setCellValue('A' . $row, $payment->FirstName . ' ' . $payment->LastName);
                        $sheet->setCellValue('B' . $row, 'PMT-' . str_pad($payment->receipt_id, 5, '0', STR_PAD_LEFT));
                        $sheet->setCellValue('D' . $row, $payment->amount);
                        $sheet->setCellValue('G' . $row, 'Balance Payment');
                        $sheet->setCellValue('H' . $row, Carbon::parse($payment->payment_date)->format('M d, Y'));
                        $sheet->setCellValue('I' . $row, $payment->user_name);

                        $userTotalBalancePayment += $payment->amount;
                        $sheet->getStyle("C{$row}:F{$row}")->getNumberFormat()->setFormatCode('#,##0.00');
                        $row++;
                    }
                }

                // User Summary
                $row += 1;
                $sheet->mergeCells("A{$row}:B{$row}");
                $sheet->setCellValue("A{$row}", "Summary for " . ($user ? $user->name : 'Unknown User'));
                $sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
                    'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '4472C4'],
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);

                $row++;
                // Add user totals
                $userTotalSales = $userTotalCashSales + $userTotalBalancePayment + $userTotalOnlinePayment;

                // Style for summary rows
                $summaryRowStyle = [
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
                ];


                $sheet->setCellValue("A{$row}", "Total Cash Sales:");
                $sheet->setCellValue("B{$row}", $userTotalCashSales);
                $sheet->getStyle("A{$row}:B{$row}")->applyFromArray($summaryRowStyle);
                $row++;

                // Total Balance Payment
                $sheet->setCellValue("A{$row}", "Total Balance Payment:");
                $sheet->setCellValue("B{$row}", $userTotalBalancePayment);
                $sheet->getStyle("A{$row}:B{$row}")->applyFromArray($summaryRowStyle);
                $row++;

                // Total Online Payment
                $sheet->setCellValue("A{$row}", "Total Online Payment:");
                $sheet->setCellValue("B{$row}", $userTotalOnlinePayment);
                $sheet->getStyle("A{$row}:B{$row}")->applyFromArray($summaryRowStyle);
                $row++;

                // Total Credit/Charge
                $sheet->setCellValue("A{$row}", "Total Credit/Charge:");
                $sheet->setCellValue("B{$row}", $userTotalCreditCharge);
                $sheet->getStyle("A{$row}:B{$row}")->applyFromArray($summaryRowStyle);
                $row++;

                // Total Sales
                $sheet->setCellValue("A{$row}", "Total Sales:");
                $sheet->setCellValue("B{$row}", $userTotalSales);
                $sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'FFFF00'],
                    ],
                    'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);
                $sheet->getStyle("B{$row}")->getNumberFormat()->setFormatCode('#,##0.00');

                // Format numbers
                $sheet->getStyle("B" . ($row - 4) . ":B" . $row)->getNumberFormat()->setFormatCode('#,##0.00');

                // Add to grand totals
                $grandTotalCashSales += $userTotalCashSales;
                $grandTotalBalancePayment += $userTotalBalancePayment;
                $grandTotalOnlinePayment += $userTotalOnlinePayment;
                $grandTotalCreditCharge += $userTotalCreditCharge;

                $row += 2; // Add space between users
            }

            // Grand Total Section
            $row += 1;
            $sheet->mergeCells("A{$row}:B{$row}");
            $sheet->setCellValue("A{$row}", "GRAND TOTALS");
            $sheet->getStyle("A{$row}:B{$row}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ]);

            $row++;
            // Grand Totals Section (continued)
            $grandTotalSales = $grandTotalCashSales + $grandTotalBalancePayment + $grandTotalOnlinePayment;

            $summaryStyle = [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ],
            ];

            // Grand Total Summary Rows
            $summaryRows = [
                ['label' => 'Total Cash Sales:', 'value' => $grandTotalCashSales, 'isCredit' => false],
                ['label' => 'Total Balance Payment:', 'value' => $grandTotalBalancePayment, 'isCredit' => false],
                ['label' => 'Total Online Payment:', 'value' => $grandTotalOnlinePayment, 'isCredit' => false, 'isOnline' => true],
                ['label' => 'Total Credit/Charge:', 'value' => $grandTotalCreditCharge, 'isCredit' => true],
            ];

            foreach ($summaryRows as $summaryRow) {
                $sheet->setCellValue('A' . $row, $summaryRow['label']);
                $sheet->setCellValue('B' . $row, $summaryRow['value']);

                if ($summaryRow['isCredit']) {
                    // Red styling for credit amounts
                    $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => 'FF0000'],
                        ],
                        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
                        'borders' => [
                            'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                        ],
                    ]);
                } elseif (isset($summaryRow['isOnline']) && $summaryRow['isOnline']) {
                    // Blue styling for online payments
                    $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                        'font' => [
                            'bold' => true,
                            'color' => ['rgb' => '0000FF'],
                        ],
                        'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
                        'borders' => [
                            'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                        ],
                    ]);
                } else {
                    $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray($summaryStyle);
                }

                $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
                $row++;
            }

            // Grand Total Sales (with yellow background)
            $sheet->setCellValue('A' . $row, 'Grand Total Sales:');
            $sheet->setCellValue('B' . $row, $grandTotalSales);
            $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFF00'],
                ],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
            ]);
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

            // After Grand Total Sales with yellow background
            $row++;
            $netIncomeLess = $grandTotalSales - $netExpenses;
            $sheet->setCellValue('A' . $row, 'Net Income Less:');
            $sheet->setCellValue('B' . $row, $netIncomeLess);
            $sheet->getStyle('A' . $row . ':A' . $row)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '92D050'], // Green background
                ],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ],
            ]);
            $sheet->getStyle('B' . $row)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '92D050'], // Green background
                ],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ],
            ]);
            $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0.00');




            // Expenses Section - Starting at column J
            $sheet->mergeCells('J1:L1');
            $sheet->setCellValue('J1', 'EXPENSES');
            $sheet->getStyle('J1:L1')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ]);

            // Expense Headers
            $expenseHeaders = ['Description', 'Amount', 'Status'];
            foreach ($expenseHeaders as $index => $header) {
                $sheet->setCellValue(chr(74 + $index) . '2', $header);
            }
            $sheet->getStyle('J2:L2')->applyFromArray([
                'font' => ['bold' => true],
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ],
            ]);

            // Group expenses by user and display
            $expenseRow = 3;
            foreach ($userExpenses as $userId => $userExps) {
                $user = User::find($userId);

                // User section header in expenses
                $sheet->mergeCells("J{$expenseRow}:L{$expenseRow}");
                $sheet->setCellValue("J{$expenseRow}", "Expenses for: " . ($user ? $user->name : 'Unknown User'));
                $sheet->getStyle("J{$expenseRow}:L{$expenseRow}")->applyFromArray([
                    'font' => ['bold' => true],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D9D9D9'],
                    ],
                ]);
                $expenseRow++;

                foreach ($userExps as $expense) {
                    $sheet->setCellValue('J' . $expenseRow, $expense->e_description);
                    $sheet->setCellValue('K' . $expenseRow, $expense->e_amount);
                    $sheet->setCellValue('L' . $expenseRow, $expense->e_return_status ? 'Returned' : 'Active');

                    $sheet->getStyle('J' . $expenseRow . ':L' . $expenseRow)->applyFromArray([
                        'borders' => [
                            'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                        ],
                    ]);
                    $sheet->getStyle('K' . $expenseRow)->getNumberFormat()->setFormatCode('#,##0.00');

                    $expenseRow++;
                }

                // User expense subtotals
                $userTotalExpenses = $userExps->sum('e_amount');
                $userTotalReturns = $userExps->sum('e_return_amount');
                $userNetExpenses = $userTotalExpenses - $userTotalReturns;

                $sheet->mergeCells("J{$expenseRow}:K{$expenseRow}");
                $sheet->setCellValue("J{$expenseRow}", "Total Expenses:");
                $sheet->setCellValue("L{$expenseRow}", $userTotalExpenses);
                $expenseRow++;

                $sheet->mergeCells("J{$expenseRow}:K{$expenseRow}");
                $sheet->setCellValue("J{$expenseRow}", "Total Returns:");
                $sheet->setCellValue("L{$expenseRow}", $userTotalReturns);
                $expenseRow++;

                $sheet->mergeCells("J{$expenseRow}:K{$expenseRow}");
                $sheet->setCellValue("J{$expenseRow}", "Net Expenses:");
                $sheet->setCellValue("L{$expenseRow}", $userNetExpenses);
                $expenseRow++;

                // Add spacing between users
                $expenseRow++;
            }

            // Grand total expenses
            $sheet->mergeCells("J{$expenseRow}:L{$expenseRow}");
            $sheet->setCellValue("J{$expenseRow}", "GRAND TOTAL EXPENSES");
            $sheet->getStyle("J{$expenseRow}:L{$expenseRow}")->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ]);
            $expenseRow++;

            $sheet->mergeCells("J{$expenseRow}:K{$expenseRow}");
            $sheet->setCellValue("J{$expenseRow}", "Total Expenses:");
            $sheet->setCellValue("L{$expenseRow}", $totalExpenses);
            $expenseRow++;

            $sheet->mergeCells("J{$expenseRow}:K{$expenseRow}");
            $sheet->setCellValue("J{$expenseRow}", "Total Returns:");
            $sheet->setCellValue("L{$expenseRow}", $totalReturns);
            $expenseRow++;

            $sheet->mergeCells("J{$expenseRow}:K{$expenseRow}");
            $sheet->setCellValue("J{$expenseRow}", "Net Expenses:");
            $sheet->setCellValue("L{$expenseRow}", $netExpenses);

            // Style the grand total rows
            $sheet->getStyle("J{$expenseRow}:L{$expenseRow}")->applyFromArray([
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ],
            ]);

            // Add column widths for expense section
            $sheet->getColumnDimension('J')->setWidth(20);
            $sheet->getColumnDimension('K')->setWidth(15);
            $sheet->getColumnDimension('L')->setWidth(15);

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
                $dateForFilename = $startDate->format('Y-m-d') . '_to_' . $endDate->format('Y-m-d');
            }
            $timestamp = Carbon::now()->format('H-i-s');

            $filename = "daily_sales_report_all_users_{$dateForFilename}_{$timestamp}.xlsx";

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
            \Log::error('Export error details:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            // Return more detailed error message in development
            if (config('app.debug')) {
                return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
            }
            return redirect()->back()->with('error', 'Failed to generate report. Please try again.');
        }

    }
}