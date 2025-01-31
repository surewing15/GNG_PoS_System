<?php

namespace App\Exports;

use Maatwebsite\Laravel\Excel\Concerns\FromCollection;
use Maatwebsite\Laravel\Excel\Concerns\WithHeadings;
use Maatwebsite\Laravel\Excel\Concerns\WithStyles;
use Maatwebsite\Laravel\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Carbon\Carbon;

class DailySalesExport implements FromCollection, WithHeadings, WithStyles, WithColumnWidths
{
    protected $transactionItems;
    protected $summaryData;

    public function __construct($transactionItems, $summaryData)
    {
        $this->transactionItems = $transactionItems;
        $this->summaryData = $summaryData;
    }

    public function collection()
    {
        // Header rows
        $data = [
            ['GLENCY\'S DRESSED CHICKEN AND TRADING'],
            ['DAILY SALES REPORT'],
            ['DATE: ' . Carbon::now()->format('F d, Y')],
            ['SALES TRANSACTIONS'],
            [
                'CUSTOMER\'S NAME',
                'RECEIPT #',
                'CASH SALES',
                'BAL. PAYMENT',
                'ONLINE',
                'CREDIT/CHARGE',
                'REMARKS',
                'DATE'
            ]
        ];

        // Transaction data
        foreach ($this->transactionItems as $item) {
            $data[] = [
                $item->transaction->customer->FirstName . ' ' . $item->transaction->customer->LastName,
                $item->transaction->receipt_id,
                $item->transaction->payment_type === 'cash' ? $item->total : '',
                $item->transaction->payment_type === 'balance' ? $item->total : '',
                $item->transaction->payment_type === 'online' ? $item->total : '',
                $item->transaction->payment_type === 'credit' ? $item->total : '',
                '',
                Carbon::parse($item->created_at)->format('M d, Y')
            ];
        }

        // Add empty rows
        $data = array_merge($data, array_fill(0, 3, ['']));

        // Summary section
        $data = array_merge($data, [
            ['SUMMARY'],
            ['Total Cash Sales:', $this->summaryData['totalCash']],
            ['Total Balance Payment:', $this->summaryData['totalBalance']],
            ['Total Online Payment:', $this->summaryData['totalOnline']],
            ['Total Credit/Charge:', $this->summaryData['totalCredit']],
            ['Total Sales:', $this->summaryData['totalSales']],
        ]);

        return collect($data);
    }

    public function headings(): array
    {
        return []; // Headings are included in collection
    }

    public function columnWidths(): array
    {
        return [
            'A' => 30,
            'B' => 15,
            'C' => 15,
            'D' => 15,
            'E' => 15,
            'F' => 15,
            'G' => 20,
            'H' => 15,
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // Title styles
        $sheet->mergeCells('A1:H1');
        $sheet->mergeCells('A2:H2');
        $sheet->mergeCells('A3:H3');

        $sheet->getStyle('A1:H3')->applyFromArray([
            'font' => ['bold' => true, 'size' => 14],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '1E4F7C']
            ],
            'font' => [
                'color' => ['rgb' => 'FFFFFF']
            ]
        ]);

        // Transactions header
        $sheet->mergeCells('A4:H4');
        $sheet->getStyle('A4')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'font' => [
                'color' => ['rgb' => 'FFFFFF']
            ]
        ]);

        // Column headers
        $sheet->getStyle('A5:H5')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'font' => [
                'color' => ['rgb' => 'FFFFFF']
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN
                ]
            ]
        ]);

        // Summary section
        $summaryStartRow = 6 + count($this->transactionItems) + 3;
        $sheet->mergeCells("A{$summaryStartRow}:H{$summaryStartRow}");
        $sheet->getStyle("A{$summaryStartRow}")->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => '4472C4']
            ],
            'font' => [
                'color' => ['rgb' => 'FFFFFF']
            ]
        ]);

        // Total Sales row
        $totalSalesRow = $summaryStartRow + 5;
        $sheet->getStyle("A{$totalSalesRow}:B{$totalSalesRow}")->applyFromArray([
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'FFFF00']
            ]
        ]);

        return [
            'A5:H5' => ['font' => ['bold' => true]],
        ];
    }
}