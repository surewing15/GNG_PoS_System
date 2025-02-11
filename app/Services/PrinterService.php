<?php
namespace App\Services;

class PrinterService
{
    private $printer_name = "EPSON_LQ_310"; // Update with your printer name
    private $handle;

    public function __construct()
    {
        $this->handle = printer_open($this->printer_name);
    }

    public function print($data)
    {
        if (!$this->handle) {
            throw new \Exception("Couldn't connect to printer");
        }

        printer_set_option($this->handle, PRINTER_MODE, "RAW");

        // Initialize printer
        $init = chr(27) . "@";
        printer_write($this->handle, $init);

        // Set line spacing
        $spacing = chr(27) . "3" . chr(18);
        printer_write($this->handle, $spacing);

        // Write data
        printer_write($this->handle, $data);

        // Form feed
        printer_write($this->handle, chr(12));

        printer_close($this->handle);
    }
}