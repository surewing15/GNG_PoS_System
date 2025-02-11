<?php

namespace App\Http\Controllers;

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class PrinterController extends Controller
{
    public function testPrint()
    {
        try {
            $connector = new WindowsPrintConnector("EPSON LQ-310");
            $printer = new Printer($connector);

            $printer->initialize();
            $printer->text("PRINTER TEST\n");
            $printer->text("=============\n\n");
            $printer->text("Time: " . date('H:i:s') . "\n");
            $printer->text("Date: " . date('d/m/Y') . "\n");
            $printer->text("Print Test Line\n");
            $printer->feed(2);
            $printer->close();

            return response()->json(['status' => 'success', 'message' => 'Print test completed']);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Printing failed: ' . $e->getMessage()
            ], 500);
        }
    }
}