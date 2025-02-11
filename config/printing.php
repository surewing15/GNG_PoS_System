<?php

return [
    'printer' => [
        'name' => env('PRINTER_NAME', 'XP-58'),
        'connection' => env('PRINTER_CONNECTION', 'usb'),
        'width' => env('PRINTER_WIDTH', 32), // Characters per line
        'ip' => env('PRINTER_IP', null),
        'port' => env('PRINTER_PORT', 9100),
        'connector' => env('PRINTER_CONNECTOR', 'WindowsPrintConnector')
    ]
];
