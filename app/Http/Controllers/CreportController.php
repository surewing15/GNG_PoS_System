<?php

namespace App\Http\Controllers;

use App\Models\TransactionItemModel;
use App\Models\CashDenominationModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\ExpenseModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        // Query builder with date range
        $query = TransactionItemModel::with(['transaction.customer', 'product'])
            ->where('user_id', $userId)
            ->whereBetween('created_at', [$startDate, $endDate]);

        if ($paymentType) {
            $query->whereHas('transaction', function ($q) use ($paymentType) {
                $q->where('payment_type', $paymentType);
            });
        }

        $transactionItems = $query->get();

        // Calculate summaries with conditional total calculation
        $totalItems = $transactionItems->count();
        $totalKilos = $transactionItems->sum('kilos');
        $totalSales = $transactionItems->sum(function ($item) {
            return $item->transaction->payment_type === 'advance_payment'
                ? $item->transaction->amount_paid
                : $item->total;
        });
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

    // cashier report
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
                        'amount_paid',
                        'change_amount',
                        'reference_number',
                        'user_id'
                    );
                },
                'transaction.customer',
                'product'
            ])
                ->whereHas('transaction', function ($query) use ($startDate, $endDate, $userId) {
                    $query->whereBetween('created_at', [$startDate, $endDate])
                        ->where('user_id', $userId);
                });

            if ($paymentType) {
                $query->whereHas('transaction', function ($q) use ($paymentType) {
                    $q->where('payment_type', $paymentType);
                });
            }

            $transactions = $query->get();

            $expenses = ExpenseModel::where('user_id', $userId)
                ->whereBetween('created_at', [$startDate, $endDate])
                ->get();

            $totalExpenses = $expenses->sum('e_amount');
            $totalReturns = $expenses->sum('e_return_amount');
            $netExpenses = $totalExpenses - $totalReturns;

            $balancePayments = DB::table('tbl_payment')
                ->join('tbl_customers', 'tbl_payment.customer_id', '=', 'tbl_customers.CustomerID')
                ->where('tbl_payment.user_id', $userId)  // Add this line
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

            $nonEggTransactions = $transactions->filter(function ($transaction) {
                return !($transaction->product && $transaction->product->category === 'Egg');
            });

            // Calculate totals by payment type
            $totalCashSales = $nonEggTransactions
                ->filter(function ($transaction) {
                    // Remove debit from cash sales calculation
                    return in_array($transaction->transaction->payment_type, ['cash', 'advance_payment']); // Removed 'debit'
                })
                ->map(function ($transaction) {
                    if ($transaction->transaction->payment_type === 'advance_payment') {
                        return $transaction->transaction->amount_paid;
                    }
                    return $transaction->transaction->credit_charge > 0
                        ? $transaction->total - $transaction->transaction->credit_charge
                        : $transaction->total;
                })
                ->sum();
            $totalBalancePayment = $balancePayments->sum('amount');
            $totalOnlinePayment = $nonEggTransactions
                ->filter(function ($transaction) {
                    return $transaction->transaction->payment_type === 'online';
                })
                ->sum('total');

            // Modified credit charge calculation to group by transaction first
            $creditTransactions = $nonEggTransactions->where('transaction.payment_type', 'credit')
                ->groupBy('transaction.transaction_id')
                ->map(function ($items) {
                    $transaction = $items->first()->transaction;
                    return $transaction->credit_charge ?? $items->sum('total');
                });
            $totalDebitCharge = $nonEggTransactions
                ->filter(function ($transaction) {
                    return $transaction->transaction->payment_type === 'debit';
                })
                ->sum('total');
            $totalCreditCharge = $nonEggTransactions->filter(function ($transaction) {
                return $transaction->transaction->payment_type === 'credit' ||
                    ($transaction->transaction->credit_charge && $transaction->transaction->credit_charge > 0);
            })->groupBy('transaction.transaction_id')
                ->map(function ($group) {
                    $firstTransaction = $group->first()->transaction;
                    return $firstTransaction->credit_charge ?? $group->sum('total');
                })->sum();

            // Calculate total sales after regrouping credit charges

            $totalSales = $totalCashSales + $totalBalancePayment + $totalOnlinePayment + $totalReturns;
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


            $row = 6;
            $nonEggTransactions = $transactions->filter(function ($transaction) {
                return !($transaction->product && $transaction->product->category === 'Egg');
            });

            $groupedTransactions = $nonEggTransactions->groupBy('transaction.receipt_id');

            foreach ($groupedTransactions as $receiptId => $items) {
                $firstItem = $items->first();

                $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);

                $customer = $firstItem->transaction->customer;
                $receiptId = $firstItem->transaction->receipt_id;
                $paymentType = $firstItem->transaction->payment_type;
                $createdAt = $firstItem->created_at;

                $sheet->setCellValue('A' . $row, $customer->FirstName . ' ' . $customer->LastName);
                $sheet->setCellValue('B' . $row, $receiptId);

                $totalAmount = $items->sum('total');
                $creditCharge = $firstItem->transaction->credit_charge ?? 0;
                $amountPaid = $firstItem->transaction->amount_paid ?? $totalAmount;
                // Make sure to get the change_amount
                $changeAmount = $firstItem->transaction->change_amount ?? 0;

                switch ($paymentType) {
                    case 'online':
                        $sheet->setCellValue('E' . $row, $totalAmount);
                        $sheet->setCellValue('G' . $row, 'Online Payment' .
                            ($firstItem->transaction->reference_number ? ' (Ref: ' . $firstItem->transaction->reference_number . ')' : ''));
                        $sheet->getStyle('E' . $row)->applyFromArray([
                            'font' => [
                                'color' => ['rgb' => '0000FF'],
                            ],
                        ]);
                        break;

                    case 'debit':
                        $sheet->setCellValue('F' . $row, $totalAmount);
                        break;

                    case 'cash':
                        // Subtract change_amount from the cash amount
                        $cashAmount = $amountPaid > 0 ?
                            ($amountPaid - $changeAmount) :
                            ($totalAmount - $creditCharge - $changeAmount);

                        $sheet->setCellValue('C' . $row, $cashAmount);

                        if ($creditCharge > 0) {
                            $sheet->setCellValue('F' . $row, $creditCharge);
                        }
                        break;

                    case 'advance_payment':
                        // Also subtract change_amount here
                        $sheet->setCellValue('C' . $row, $amountPaid - $changeAmount);

                        if ($creditCharge > 0) {
                            $sheet->setCellValue('F' . $row, $creditCharge);
                        }
                        $sheet->setCellValue('G' . $row, 'Advance Payment');
                        break;

                    case 'credit':
                        $sheet->setCellValue('F' . $row, $creditCharge ?? $totalAmount);
                        $sheet->setCellValue('G' . $row, 'Credit Transaction');
                        break;
                }

                $sheet->setCellValue('H' . $row, Carbon::parse($createdAt)->format('M d, Y'));
                $sheet->getStyle('C' . $row . ':F' . $row)->getNumberFormat()
                    ->setFormatCode('#,##0.00');

                $row++;
            }


            $totalCashSales = 0;
            foreach ($groupedTransactions as $receiptId => $items) {
                $firstItem = $items->first();
                $paymentType = $firstItem->transaction->payment_type;

                if (in_array($paymentType, ['cash', 'advance_payment'])) {
                    $totalAmount = $items->sum('total');
                    $creditCharge = $firstItem->transaction->credit_charge ?? 0;
                    $amountPaid = $firstItem->transaction->amount_paid ?? $totalAmount;
                    $changeAmount = $firstItem->transaction->change_amount ?? 0;

                    if ($paymentType === 'advance_payment') {
                        $cashAmount = $amountPaid - $changeAmount;
                    } else {
                        $cashAmount = $amountPaid > 0 ?
                            ($amountPaid - $changeAmount) :
                            ($totalAmount - $creditCharge - $changeAmount);
                    }

                    $totalCashSales += $cashAmount;
                }
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


            $row++;
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
                ['label' => 'Total Returns:', 'value' => $totalReturns, 'isCredit' => false],
                ['label' => 'Total Online Payment:', 'value' => $totalOnlinePayment, 'isCredit' => false, 'isOnline' => true],
                ['label' => 'Total Credit/Charge:', 'value' => $totalCreditCharge, 'isCredit' => true],
            ];

            foreach ($summaryRows as $summaryRow) {
                $sheet->setCellValue('A' . $row, $summaryRow['label']);
                $sheet->setCellValue('B' . $row, $summaryRow['value']);

                // Special styling for different types
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
                    // Regular styling for other rows
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

            // Save the main row position before working on the right columns
            $mainRowPosition = $row;

            // Expenses Section - Using hardcoded positions in columns J-L
            $sheet->mergeCells('J17:L17');
            $sheet->setCellValue('J17', 'EXPENSES');
            $sheet->getStyle('J17:L17')->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ]);

            // Expense Headers
            $expenseHeaders = ['Description', 'Amount', 'Status'];
            foreach ($expenseHeaders as $index => $header) {
                $sheet->setCellValue(chr(74 + $index) . '18', $header); // 74 is ASCII for 'J'
            }
            $sheet->getStyle('J18:L18')->applyFromArray([
                'font' => ['bold' => true],
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ],
            ]);

            // Expense Data
            $expenseRow = 19;
            foreach ($expenses as $expense) {
                $sheet->setCellValue('J' . $expenseRow, $expense->e_description);
                $sheet->setCellValue('K' . $expenseRow, $expense->e_amount);

                // Determine status
                $status = $expense->e_return_status ? 'Returned' : 'Active';
                $sheet->setCellValue('L' . $expenseRow, $status);

                // Apply borders and number format
                $sheet->getStyle('J' . $expenseRow . ':L' . $expenseRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);
                $sheet->getStyle('K' . $expenseRow)->getNumberFormat()->setFormatCode('#,##0.00');

                $expenseRow++;
            }

            // Add expense totals
            $totalRow = $expenseRow;
            $sheet->mergeCells('J' . $totalRow . ':K' . $totalRow);
            $sheet->setCellValue('J' . $totalRow, 'Total Expenses:');
            $sheet->setCellValue('L' . $totalRow, $totalExpenses);
            $sheet->getStyle('L' . $totalRow)->getNumberFormat()->setFormatCode('#,##0.00');

            // Style the totals rows
            $sheet->getStyle('J' . ($totalRow - 2) . ':L' . $totalRow)->applyFromArray([
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

            // Start Denomination Section at J1 with a separate row counter
            $denomRow = 1;

            $sheet->mergeCells('J' . $denomRow . ':L' . $denomRow);
            $sheet->setCellValue('J' . $denomRow, 'DENOMINATION');
            $sheet->getStyle('J' . $denomRow . ':L' . $denomRow)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ]);

            // Denomination headers
            $denomRow++;
            $sheet->setCellValue('J' . $denomRow, 'CASH');
            $sheet->setCellValue('K' . $denomRow, 'PIECES');
            $sheet->setCellValue('L' . $denomRow, 'TOTAL');
            $sheet->getStyle('J' . $denomRow . ':L' . $denomRow)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'D9D9D9'],
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ],
            ]);

            // Get denomination data for the date
            $denomination = CashDenominationModel::where('user_id', $userId)
                ->where('count_date', $endDate->format('Y-m-d'))
                ->first();

            // Denomination values
            $denominations = [
                1000 => 'd1000',
                500 => 'd500',
                200 => 'd200',
                100 => 'd100',
                50 => 'd50',
                20 => 'd20',
                10 => 'd10',
                5 => 'd5',
                1 => 'd1',
                0.25 => 'd025'
            ];

            foreach ($denominations as $value => $field) {
                $denomRow++;
                $pieces = $denomination ? $denomination->$field : 0;
                $total = $value * $pieces;

                // Add borders to denomination rows
                $sheet->getStyle('J' . $denomRow . ':L' . $denomRow)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);

                $sheet->setCellValue('J' . $denomRow, $value);
                $sheet->setCellValue('K' . $denomRow, $pieces);
                $sheet->setCellValue('L' . $denomRow, $total);

                $sheet->getStyle('J' . $denomRow)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('L' . $denomRow)->getNumberFormat()->setFormatCode('#,##0.00');
            }

            // Online amount
            $denomRow++;
            $sheet->mergeCells('J' . $denomRow . ':K' . $denomRow);
            $sheet->setCellValue('J' . $denomRow, 'ONLINE:');
            $sheet->setCellValue('L' . $denomRow, $denomination ? $denomination->online_amount : 0);
            $sheet->getStyle('L' . $denomRow)->getNumberFormat()->setFormatCode('#,##0.00');
            $sheet->getStyle('J' . $denomRow . ':L' . $denomRow)->applyFromArray([
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ],
            ]);


            // Total
            $denomRow++;
            $sheet->mergeCells('J' . $denomRow . ':K' . $denomRow);
            $sheet->setCellValue('J' . $denomRow, 'TOTAL:');
            $sheet->setCellValue('L' . $denomRow, $denomination ? $denomination->total_amount : 0);
            $sheet->getStyle('J' . $denomRow . ':L' . $denomRow)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFF00'],
                ],
            ]);
            $sheet->getStyle('L' . $denomRow)->getNumberFormat()->setFormatCode('#,##0.00');

            $denomRow++;
            $totalCashSales = $totalCashSales ?? 0; // Ensure variable exists
            $denominationTotal = $denomination ? $denomination->total_amount : 0;
            $cashShortage = $denominationTotal - $totalCashSales;

            // Set the SHORT/EXCESS CASH row
            $sheet->mergeCells('J' . $denomRow . ':K' . $denomRow);
            $sheet->setCellValue('J' . $denomRow, 'SHORT/EXCESS CASH:');
            $sheet->setCellValue('L' . $denomRow, $cashShortage);

            // Apply formatting with conditional color (red for shortage, green for excess)
            $textColor = $cashShortage < 0 ? 'FF0000' : '008000'; // Red for negative, Green for positive
            $sheet->getStyle('J' . $denomRow . ':L' . $denomRow)->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => $textColor],
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ],
            ]);
            $sheet->getStyle('L' . $denomRow)->getNumberFormat()->setFormatCode('#,##0.00');




            // Continue with the main column from the saved position
            $row = $mainRowPosition;

            // Add Egg Products Section - add some spacing after summary
            $row += 2;
            $sheet->mergeCells('A' . $row . ':H' . $row);
            $sheet->setCellValue('A' . $row, 'EGG PRODUCTS TRANSACTIONS');
            $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ]);

            // Column Headers for Egg Products
            $row++;
            $headers = [
                'A' => "CUSTOMER'S NAME",
                'B' => 'RECEIPT #',
                'C' => 'CASH SALES',
                'D' => 'BAL. PAYMENT',
                'E' => 'ONLINE',
                'F' => 'CREDIT/CHARGE',
                'G' => 'REMARKS',
                'H' => 'DATE'
            ];

            foreach ($headers as $col => $value) {
                $sheet->setCellValue($col . $row, $value);
            }

            $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '305496'],
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ],
            ]);

            // Filter transactions for egg products
            $eggTransactions = $transactions->filter(function ($transaction) {
                return $transaction->product && $transaction->product->category === 'Egg';
            });

            // Data Rows for Egg Products
            $row++;
            $totalEggCash = 0;
            $totalEggBalance = 0;
            $totalEggOnline = 0;
            $totalEggCredit = 0;

            foreach ($eggTransactions as $transaction) {
                $sheet->getStyle('A' . $row . ':H' . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);

                $sheet->setCellValue('A' . $row, $transaction->transaction->customer->FirstName . ' ' .
                    $transaction->transaction->customer->LastName);
                $sheet->setCellValue('B' . $row, $transaction->transaction->receipt_id);

                switch ($transaction->transaction->payment_type) {
                    case 'online':
                        $amount = $transaction->total;
                        $sheet->setCellValue('E' . $row, $amount);
                        $totalEggOnline += $amount;
                        $sheet->setCellValue('G' . $row, 'Online Payment' .
                            ($transaction->transaction->reference_number ? ' (Ref: ' . $transaction->transaction->reference_number . ')' : ''));
                        break;

                    case 'debit':
                        // For debit payments, show amount in credit/charge column
                        $amount = $transaction->total;
                        $sheet->setCellValue('F' . $row, $amount);
                        $totalEggCredit += $amount; // Add to egg credit total
                        break;

                    case 'cash':
                        $cashAmount = $transaction->transaction->credit_charge > 0
                            ? $transaction->total - $transaction->transaction->credit_charge
                            : $transaction->total;
                        $sheet->setCellValue('C' . $row, $cashAmount);
                        $totalEggCash += $cashAmount;
                        if ($transaction->transaction->credit_charge > 0) {
                            $sheet->setCellValue('F' . $row, $transaction->transaction->credit_charge);
                            $totalEggCredit += $transaction->transaction->credit_charge;
                        }
                        break;

                    case 'credit':
                        $creditAmount = $transaction->transaction->credit_charge ?? $transaction->total;
                        $sheet->setCellValue('F' . $row, $creditAmount);
                        $totalEggCredit += $creditAmount;
                        $sheet->setCellValue('G' . $row, 'Credit Transaction');
                        break;
                }

                $sheet->setCellValue('H' . $row, Carbon::parse($transaction->created_at)->format('M d, Y'));
                $sheet->getStyle('C' . $row . ':F' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

                $row++;
            }

            // Add Egg Products Summary
            $row++;
            $sheet->mergeCells('A' . $row . ':B' . $row);
            $sheet->setCellValue('A' . $row, 'EGG PRODUCTS SUMMARY');
            $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray([
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '4472C4'],
                ],
            ]);

            $summaryStyle = [
                'font' => ['bold' => true],
                'alignment' => ['horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT],
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ],
            ];

            // Add summary rows
            $row++;
            $summaryRows = [
                ['label' => 'Total Egg Cash Sales:', 'value' => $totalEggCash],
                ['label' => 'Total Egg Online Payment:', 'value' => $totalEggOnline],
                ['label' => 'Total Egg Credit/Charge:', 'value' => $totalEggCredit],
            ];

            foreach ($summaryRows as $summaryRow) {
                $sheet->setCellValue('A' . $row, $summaryRow['label']);
                $sheet->setCellValue('B' . $row, $summaryRow['value']);
                $sheet->getStyle('A' . $row . ':B' . $row)->applyFromArray($summaryStyle);
                $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
                $row++;
            }

            // Total Egg Sales
            $totalEggSales = $totalEggCash + $totalEggOnline;
            $sheet->setCellValue('A' . $row, 'Total Egg Sales:');
            $sheet->setCellValue('B' . $row, $totalEggSales);
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


            if ($startDate->isSameDay($endDate)) {
                $dateForFilename = $startDate->format('Y-m-d');
            } else {
                $dateForFilename = $startDate->format('Y-m-d') . '_to_' .
                    $endDate->format('Y-m-d');
            }
            $timestamp = Carbon::now()->format('H-i-s');

            $filename = "daily_sales_report_{$generatorName}_{$dateForFilename}_{$timestamp}.xlsx";

            ob_start();
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            $content = ob_get_contents();
            ob_end_clean();

            return response($content)
                ->header('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                ->header('Content-Disposition', 'attachment; filename="' . $filename . '"')
                ->header('Cache-Control', 'max-age=0');

        } catch (\PhpOffice\PhpSpreadsheet\Exception $e) {
            Log::error('Spreadsheet error in export:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Error in spreadsheet generation: ' . $e->getMessage());
        } catch (\Illuminate\Database\QueryException $e) {
            Log::error('Database error in export:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Database error occurred: ' . $e->getMessage());
        } catch (\Exception $e) {
            Log::error('General error in export:', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            if (config('app.debug')) {
                return redirect()->back()->with('error', 'Error: ' . $e->getMessage());
            }
            return redirect()->back()->with('error', 'Failed to generate report. Please try again.');
        }
    }
    public function storeDenomination(Request $request)
    {
        try {
            $request->validate([
                'count_date' => 'required|date',
                'd1000' => 'required|integer|min:0',
                'd500' => 'required|integer|min:0',
                'd200' => 'required|integer|min:0',
                'd100' => 'required|integer|min:0',
                'd50' => 'required|integer|min:0',
                'd20' => 'required|integer|min:0',
                'd10' => 'required|integer|min:0',
                'd5' => 'required|integer|min:0',
                'd1' => 'required|integer|min:0',
                'd025' => 'required|integer|min:0',
                'online_amount' => 'required|numeric|min:0'
            ]);

            $cashTotal =
                ($request->d1000 * 1000) +
                ($request->d500 * 500) +
                ($request->d200 * 200) +
                ($request->d100 * 100) +
                ($request->d50 * 50) +
                ($request->d20 * 20) +
                ($request->d10 * 10) +
                ($request->d5 * 5) +
                ($request->d1 * 1) +
                ($request->d025 * 0.25);

            $totalAmount = $cashTotal + $request->online_amount;

            $denomination = CashDenominationModel::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'count_date' => $request->count_date,
                ],
                [
                    'd1000' => $request->d1000,
                    'd500' => $request->d500,
                    'd200' => $request->d200,
                    'd100' => $request->d100,
                    'd50' => $request->d50,
                    'd20' => $request->d20,
                    'd10' => $request->d10,
                    'd5' => $request->d5,
                    'd1' => $request->d1,
                    'd025' => $request->d025,
                    'online_amount' => $request->online_amount,
                    'total_amount' => $totalAmount
                ]
            );

            return response()->json([
                'success' => true,
                'cash_total' => $cashTotal,
                'total_amount' => $totalAmount,
                'denomination' => $denomination
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error saving denomination count'
            ], 500);
        }
    }
    public function denominationIndex()
    {
        $userId = Auth::id();
        $today = Carbon::now()->format('Y-m-d');

        $denomination = CashDenominationModel::where('user_id', $userId)
            ->where('count_date', $today)
            ->first();

        return view('cashier.pages.denomination.index', compact('denomination'));
    }

}