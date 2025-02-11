<?php
namespace App\Services;

use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;
use Exception;

class OrderPrinterService
{
    private $printer;
    private $connector;
    private const MAX_RETRIES = 3;
    private const RETRY_DELAY = 1;

    public function __construct()
    {
        $this->initializePrinter();
    }

    private function initializePrinter()
    {
        if ($this->printer !== null) {
            return;
        }

        try {
            $this->connector = new WindowsPrintConnector("EPSON LQ-310");
            $this->printer = new Printer($this->connector);
            \Log::info("Printer connected successfully");
        } catch (Exception $e) {
            \Log::error("Printer initialization error: " . $e->getMessage());
            throw $e;
        }
    }

    public function printReceipt(array $data): void
    {
        try {
            $this->printer->initialize();

            // Select 12 CPI font size (for 9.5" width)
            $this->printer->text("\x1B\x4D"); // Select 12 CPI
            $this->printer->text("\x1B\x32"); // Set 1/6" line spacing

            // Header
            $this->printer->text("3GLG CHICKEN PRODUCING\n");
            $this->printer->text("Zone 4 Sta. Cruz, Tagoloan, Mis. Or.\n\n");

            // Order List Header - Using tab positions for 9.5" width
            $this->printer->text("ORDER LIST");
            $this->printer->text(str_pad("SALES ORDER: " . $data['receipt_id'], 50) . "\n");

            // Customer Details with fixed column positions
            $this->printer->text("Ordered By : " . $data['customer_name']);
            $this->printer->text(str_pad("Date : " . date('m/d/Y'), 30, " ", STR_PAD_LEFT) . "\n");
            $this->printer->text("Address    : " . ($data['address'] ?? ''));
            $this->printer->text(str_pad("Terms: 0 day(s)", 30, " ", STR_PAD_LEFT) . "\n\n");

            // Column Headers with fixed positions for 9.5" width
            $this->printer->text("Qty         UOM      Desc                                      Amount\n");
            $this->printer->text("Amount Due\n");
            $this->printer->text(str_repeat("-", 80) . "\n");

            // Items with fixed column positions
            foreach ($data['items'] as $item) {
                $this->printer->text(
                    str_pad(number_format($item['kilos'], 2), 12) .
                    str_pad("KL", 10) .
                    str_pad($item['sku'], 40) .
                    str_pad(number_format($item['price_per_kilo'], 2), 18, " ", STR_PAD_LEFT) . "\n" .
                    str_pad(number_format($item['total'], 2), 12) . "\n"
                );
            }

            // Total aligned with first column
            $this->printer->text("TOTAL\n");
            $this->printer->text(str_pad(number_format($data['total_amount'], 2), 12) . "\n\n");

            // Payment details aligned to the left with fixed spacing
            $this->printer->text("CASH PAYMENT:\n");
            $this->printer->text(str_pad(number_format($data['amount_paid'] ?? 0, 2), 12) . "\n");
            $this->printer->text("CHECK PAYMENT:\n");
            $this->printer->text(str_pad("0.00", 12) . "\n");
            $this->printer->text("ONLINE PAYMENT:\n");
            $this->printer->text(str_pad("0.00", 12) . "\n");
            $this->printer->text("ADVANCE PAYMENT:\n");
            $this->printer->text(str_pad("0.00", 12) . "\n");
            $this->printer->text("REMAINING BALANCE:\n");
            $this->printer->text(str_pad("0.00", 12) . "\n\n");

            // Confirmation text with proper line breaks for 9.5" width
            $this->printer->text("I, " . $data['customer_name'] . ", hereby confirm that the item(s) listed above have been recei\n");
            $this->printer->text("ved in good\n");
            $this->printer->text("condition and in the quantities stated.\n\n");

            // Signature lines with fixed positions
            $this->printer->text("Prepared By:" . str_pad("Checked By:", 35, " ", STR_PAD_LEFT) .
                str_pad("Received By:", 35, " ", STR_PAD_LEFT) . "\n\n");

            $this->printer->text("_____________" . str_pad("_____________", 35, " ", STR_PAD_LEFT) .
                str_pad("_____________", 35, " ", STR_PAD_LEFT) . "\n");

            $this->printer->text($data['customer_name'] . str_pad("(Signature over printed name)", 35, " ", STR_PAD_LEFT) .
                str_pad($data['customer_name'], 35, " ", STR_PAD_LEFT) . "\n\n");

            // Footer with fixed positions
            $this->printer->text("Printed By: " . auth()->user()->name . " " . date('n/j/Y g:i:s A') . "\n");
            $this->printer->text("Page: 1 of 1\n");
            $this->printer->text("VA\n");

            // Form feed for continuous paper
            $this->printer->text("\x0C"); // Form feed
            $this->printer->close();

        } catch (Exception $e) {
            \Log::error("Print error: " . $e->getMessage());
            throw $e;
        }
    }
    public function __destruct()
    {
        // Ensure printer connection is closed when object is destroyed
        if ($this->printer !== null) {
            try {
                $this->printer->close();
            } catch (Exception $e) {
                \Log::error("Error closing printer: " . $e->getMessage());
            }
        }
    }
}