<?php
namespace App\Services;

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;

class ReceiptPrinter
{
    private function printContent($printer, $transaction)
    {
        try {
            $printer->initialize();

            // Header
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            $printer->text("Company Name\n");
            $printer->text("Receipt\n");
            $printer->text(str_repeat("-", 32) . "\n");

            // Transaction Details
            $printer->setJustification(Printer::JUSTIFY_LEFT);
            $printer->text("Receipt #: " . $transaction->receipt_id . "\n");
            $printer->text("Date: " . $transaction->date . "\n");
            $printer->text("Customer: " . $transaction->customer->FirstName . " " . $transaction->customer->LastName . "\n");
            $printer->text(str_repeat("-", 32) . "\n");

            // Items
            foreach ($transaction->items as $item) {
                $printer->text($item->product->product_sku . "\n");
                $printer->text(sprintf(
                    "%.2f kg x ₱%.2f = ₱%.2f\n",
                    $item->kilos,
                    $item->price_per_kilo,
                    $item->total
                ));
            }

            $printer->text(str_repeat("-", 32) . "\n");

            // Totals
            $printer->text(sprintf("Subtotal: ₱%.2f\n", $transaction->subtotal));
            if ($transaction->discount_amount > 0) {
                $printer->text(sprintf("Discount: ₱%.2f\n", $transaction->discount_amount));
            }
            $printer->text(sprintf("Total: ₱%.2f\n", $transaction->total_amount));

            if ($transaction->payment_type === 'cash') {
                $printer->text(sprintf("Paid: ₱%.2f\n", $transaction->amount_paid));
                $printer->text(sprintf("Change: ₱%.2f\n", $transaction->change_amount));
            }

            // Footer
            $printer->text(str_repeat("-", 32) . "\n");
            $printer->text("Thank you for your purchase!\n");

            $printer->cut();

        } catch (\Exception $e) {
            \Log::error('Print content error: ' . $e->getMessage());
            throw $e;
        }
    }

    private function getPrinterConnector()
    {
        try {
            // Try USB first
            $connector = new WindowsPrintConnector("XP-58");
            return $connector;
        } catch (\Exception $e) {
            try {
                // Fallback to network printer
                $connector = new WindowsPrintConnector("smb://computer/XP-58");
                return $connector;
            } catch (\Exception $e) {
                \Log::error('Failed to connect to printer: ' . $e->getMessage());
                return null;
            }
        }
    }

    private function printContent($printer, $transaction)
    {
        // Print each section with error handling
        try {
            $printer->initialize();
            $printer->setJustification(Printer::JUSTIFY_CENTER);
            // ... rest of printing logic

        } catch (\Exception $e) {
            \Log::error('Error during content printing: ' . $e->getMessage());
            throw $e;
        }
    }
}