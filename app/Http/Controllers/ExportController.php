<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Illuminate\Support\Facades\DB;

class ExportController extends Controller
{
    public function export(Request $request, $type)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        $headers = [
            'A1' => 'Transaction ID',
            'B1' => 'Date',
            'C1' => 'Receipt ID',
            'D1' => 'Customer Name',
            'E1' => 'Phone',
            'F1' => 'Total Amount',
            'G1' => 'Subtotal',
            'H1' => 'Amount Paid',
            'I1' => 'Change Amount',
            'J1' => 'Discount %',
            'K1' => 'Discount Amount',
            'L1' => 'Service Type',
            'M1' => 'Status',
            'N1' => 'Payment Type'
        ];

        foreach ($headers as $cell => $value) {
            $sheet->setCellValue($cell, $value);
        }


        $headerStyle = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER
            ]
        ];

        $sheet->getStyle('A1:N1')->applyFromArray($headerStyle);


        foreach (range('A', 'N') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $data = $type === 'debits' ? $this->getDebits() :
            ($type === 'debit_cash' ? $this->getDebitAndCashTransactions() : $this->getSales());

        $row = 2;
        foreach ($data as $item) {

            $sheet->setCellValue('A' . $row, $item->transaction_id)
                ->setCellValue('B' . $row, $item->date)
                ->setCellValue('C' . $row, $item->receipt_id)
                ->setCellValue('D' . $row, $item->FirstName . ' ' . $item->LastName)
                ->setCellValue('E' . $row, $item->phone)
                ->setCellValue('F' . $row, $item->total_amount)
                ->setCellValue('G' . $row, $item->subtotal)
                ->setCellValue('H' . $row, $item->amount_paid)
                ->setCellValue('I' . $row, $item->change_amount)
                ->setCellValue('J' . $row, $item->discount_percentage)
                ->setCellValue('K' . $row, $item->discount_amount)
                ->setCellValue('L' . $row, $item->service_type)
                ->setCellValue('M' . $row, $item->status)
                ->setCellValue('N' . $row, $item->payment_type);

            $headerStyle = [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => new \PhpOffice\PhpSpreadsheet\Style\Color('4472C4')
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN
                    ]
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER
                ]
            ];


            if ($row % 2 == 0) {
                $sheet->getStyle("A{$row}:N{$row}")->getFill()
                    ->setFillType(Fill::FILL_SOLID)
                    ->setStartColor(new \PhpOffice\PhpSpreadsheet\Style\Color('F2F2F2'));
            }
        }


        $sheet->getStyle('A1:N' . ($row - 1))->getBorders()->getAllBorders()
            ->setBorderStyle(Border::BORDER_THIN);

        $writer = new Xlsx($spreadsheet);
        $filename = ucfirst($type) . '_Report_' . date('Y-m-d') . '.xlsx';

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
    }

    private function getDebits()
    {
        return DB::table('tbl_transactions')
            ->join('tbl_customers', 'tbl_transactions.CustomerID', '=', 'tbl_customers.CustomerID')
            ->where('payment_type', 'debit')
            ->select('tbl_transactions.*', 'tbl_customers.FirstName', 'tbl_customers.LastName')
            ->orderBy('date', 'desc')
            ->get();
    }

    private function getSales()
    {
        return DB::table('tbl_transactions')
            ->join('tbl_customers', 'tbl_transactions.CustomerID', '=', 'tbl_customers.CustomerID')
            ->select('tbl_transactions.*', 'tbl_customers.FirstName', 'tbl_customers.LastName')
            ->orderBy('date', 'desc')
            ->get();
    }

    private function getDebitAndCashTransactions()
    {
        return DB::table('tbl_transactions')
            ->join('tbl_customers', 'tbl_transactions.CustomerID', '=', 'tbl_customers.CustomerID')
            ->whereIn('payment_type', ['debit', 'cash'])
            ->select('tbl_transactions.*', 'tbl_customers.FirstName', 'tbl_customers.LastName')
            ->orderBy('date', 'desc')
            ->get();
    }
}