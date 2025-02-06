<?php

namespace App\Services;

use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer;
use Exception;

class OrderPrinterService
{
    private Printer $printer;
    private NetworkPrintConnector $connector;

    public function __construct(string $ip = "192.168.1.87", int $port = 9100)
    {
        try {
            $this->connector = new NetworkPrintConnector($ip, $port);
            $this->printer = new Printer($this->connector);
            $this->printer->setPrintWidth(241.3);
            $this->printer->setLineSpacing(6);
        } catch (Exception $e) {
            throw new Exception("Printer connection failed: " . $e->getMessage());
        }
    }

    public function printReceipt(array $data): void
    {
        try {
            $this->printer->initialize();

            // Store settings
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);
            $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $this->printer->text("3GLG CHICKEN PRODUCING\n");
            $this->printer->selectPrintMode();
            $this->printer->text("Zone 4 Sta. Cruz, Tagoloan, Mis. Or.\n\n");

            // Receipt details
            $this->printer->setJustification(Printer::JUSTIFY_LEFT);
            $this->printer->text("Receipt #: " . $data['receipt_id'] . "\n");
            $this->printer->text("Date: " . date('Y-m-d H:i:s') . "\n");
            $this->printer->text("Customer: " . $data['customer_name'] . "\n");
            $this->printer->text("Service: " . ucfirst($data['service_type']) . "\n\n");

            // Table header
            $this->printer->text(str_repeat("-", 48) . "\n");
            $this->printer->text(sprintf("%-20s %3s %6s %7s\n", "SKU", "KG", "₱/KG", "TOTAL"));
            $this->printer->text(str_repeat("-", 48) . "\n");

            // Items
            foreach ($data['items'] as $item) {
                $this->printer->text(sprintf(
                    "%-20s %3.1f %6.2f %7.2f\n",
                    substr($item['sku'], 0, 20),
                    $item['kilos'],
                    $item['price_per_kilo'],
                    $item['total']
                ));
            }

            // Totals
            $this->printer->text(str_repeat("-", 48) . "\n");
            $this->printer->setJustification(Printer::JUSTIFY_RIGHT);
            $this->printer->text(sprintf("Subtotal: ₱%.2f\n", $data['subtotal']));

            if ($data['discount_amount'] > 0) {
                $this->printer->text(sprintf("Discount: ₱%.2f\n", $data['discount_amount']));
            }

            $this->printer->selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
            $this->printer->text(sprintf("TOTAL: ₱%.2f\n", $data['total_amount']));
            $this->printer->selectPrintMode();

            // Payment details
            switch ($data['payment_type']) {
                case 'cash':
                    if (isset($data['amount_paid'])) {
                        $this->printer->text(sprintf("Cash: ₱%.2f\n", $data['amount_paid']));
                        $this->printer->text(sprintf("Change: ₱%.2f\n", $data['change_amount']));
                    }
                    break;
                case 'advance_payment':
                    if (isset($data['used_advance_payment'])) {
                        $this->printer->text(sprintf("Advance Payment: ₱%.2f\n", $data['used_advance_payment']));
                    }
                    break;
                case 'online':
                    if (isset($data['reference_number'])) {
                        $this->printer->text("Online Payment\n");
                        $this->printer->text("Ref #: " . $data['reference_number'] . "\n");
                    }
                    break;
                default:
                    $this->printer->text("Payment: " . ucfirst($data['payment_type']) . "\n");
            }

            // Footer
            $this->printer->feed(2);
            $this->printer->setJustification(Printer::JUSTIFY_CENTER);
            $this->printer->text("Thank you for your business!\n");
            $this->printer->text("Please come again\n");

            // Cut receipt
            $this->printer->cut();
            $this->printer->close();

        } catch (Exception $e) {
            throw new Exception("Print failed: " . $e->getMessage());
        }
    }
}
