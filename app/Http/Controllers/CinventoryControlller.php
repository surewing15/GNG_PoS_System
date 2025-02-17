<?php

namespace App\Http\Controllers;

use App\Models\MasterStockModel;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class CinventoryControlller extends Controller
{
    public function index()
    {
        $masterStocks = MasterStockModel::with('product')
            ->orderBy('master_stock_id', 'desc')
            ->get();

        return view('clerk.pages.inventory.index', compact('masterStocks'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'total_all_kilos' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'total_head' => 'required|numeric|min:0',
            'dr' => 'required|string'
        ]);

        $masterStock = MasterStockModel::findOrFail($id);
        $masterStock->update($request->all());

        return redirect()->back()->with('success', 'Stock updated successfully');
    }

    public function destroy($id)
    {
        $masterStock = MasterStockModel::findOrFail($id);
        $masterStock->delete();

        return redirect()->back()->with('success', 'Stock deleted successfully');
    }
    public function export()
    {
        try {
            $masterStocks = MasterStockModel::with('product')
                ->orderBy('master_stock_id', 'desc')
                ->get();

            // Create new Spreadsheet
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            // Set column widths
            $sheet->getColumnDimension('A')->setWidth(30);
            $sheet->getColumnDimension('B')->setWidth(20);
            $sheet->getColumnDimension('C')->setWidth(15);
            $sheet->getColumnDimension('D')->setWidth(15);
            $sheet->getColumnDimension('E')->setWidth(15);

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
            $sheet->mergeCells('A1:E1');
            $sheet->setCellValue('A1', "GLENCY'S DRESSED CHICKEN AND TRADING");
            $sheet->mergeCells('A2:E2');
            $sheet->setCellValue('A2', 'INVENTORY REPORT');
            $sheet->mergeCells('A3:E3');
            $sheet->setCellValue('A3', 'DATE: ' . Carbon::now()->format('F d, Y'));

            // Apply header styles
            $sheet->getStyle('A1:E3')->applyFromArray($headerStyle);
            $sheet->getStyle('A1')->getFont()->setSize(14);
            $sheet->getStyle('A2')->getFont()->setSize(12);
            $sheet->getStyle('A3')->getFont()->setSize(11);

            // Column Headers
            $headers = [
                'A5' => 'PRODUCT',
                'B5' => 'CATEGORY',
                'C5' => 'TOTAL KILOS',
                'D5' => 'TOTAL HEAD',
                'E5' => 'PRICE'
            ];

            foreach ($headers as $cell => $value) {
                $sheet->setCellValue($cell, $value);
            }

            // Apply header row styles
            $sheet->getStyle('A5:E5')->applyFromArray([
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
            foreach ($masterStocks as $stock) {
                $sheet->setCellValue('A' . $row, $stock->product->product_sku);
                $sheet->setCellValue('B' . $row, $stock->product->category);
                $sheet->setCellValue('C' . $row, $stock->total_all_kilos);
                $sheet->setCellValue('D' . $row, $stock->total_head);
                $sheet->setCellValue('E' . $row, $stock->price);

                // Apply borders
                $sheet->getStyle('A' . $row . ':E' . $row)->applyFromArray([
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                ]);

                // Format numbers
                $sheet->getStyle('C' . $row)->getNumberFormat()->setFormatCode('#,##0.00');
                $sheet->getStyle('D' . $row)->getNumberFormat()->setFormatCode('#,##0');
                $sheet->getStyle('E' . $row)->getNumberFormat()->setFormatCode('#,##0.00');

                $row++;
            }

            // Add totals
            $row++;
            $sheet->mergeCells('A' . $row . ':B' . $row);
            $sheet->setCellValue('A' . $row, 'TOTALS:');
            $sheet->setCellValue('C' . $row, '=SUM(C6:C' . ($row - 1) . ')');
            $sheet->setCellValue('D' . $row, '=SUM(D6:D' . ($row - 1) . ')');

            // Style totals row
            $sheet->getStyle('A' . $row . ':E' . $row)->applyFromArray([
                'font' => ['bold' => true],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => 'FFFF00'],
                ],
                'borders' => [
                    'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                ],
            ]);

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

            // Create filename
            $timestamp = Carbon::now()->format('Y-m-d_H-i-s');
            $filename = "inventory_report_{$generatorName}_{$timestamp}.xlsx";

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