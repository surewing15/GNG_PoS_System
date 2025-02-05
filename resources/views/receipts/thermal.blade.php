<!-- resources/views/receipts/thermal.blade.php -->
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Thermal Receipt</title>
    <style>
        /* Thermal printer specific styles */
        @page {
            size: 80mm auto;
            margin: 0;
        }

        body {
            width: 80mm;
            margin: 0;
            padding: 0;
            font-family: 'Courier New', monospace;
            font-size: 12pt;
            line-height: 1.2;
        }

        .receipt {
            width: 80mm;
            padding: 3mm;
            text-align: center;
        }

        .receipt-brand img {
            max-width: 60mm;
            height: auto;
        }

        .receipt-header {
            margin-bottom: 3mm;
            text-align: center;
        }

        .receipt-details p {
            margin: 1mm 0;
            text-align: left;
        }

        .table {
            width: 74mm;
            margin: 3mm 0;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            text-align: left;
            padding: 1mm;
            border-bottom: 1px dashed #000;
        }

        .text-end {
            text-align: right !important;
        }

        .receipt-total {
            text-align: right;
            margin: 3mm 0;
            border-top: 1px dashed #000;
        }

        .d-flex {
            display: flex;
            justify-content: space-between;
            margin: 1mm 0;
        }

        .signature-section {
            margin-top: 5mm;
            text-align: center;
        }

        .signature-line {
            margin: 5mm 0;
            padding-top: 2mm;
            border-top: 1px solid #000;
        }

        .receipt-footer {
            margin-top: 5mm;
            padding-top: 2mm;
            text-align: center;
            font-size: 10pt;
            border-top: 1px dashed #000;
        }
    </style>
</head>

<body>
    <div class="receipt">
        <!-- Logo -->
        <div class="receipt-brand">
            <img src="{{ asset('storage/logo.png') }}" alt="Logo">
        </div>

        <!-- Company Info -->
        <div class="receipt-header">
            <h4>3GLG CHICKEN PRODUCING</h4>
            <p>Zone 4 Sta. Cruz, Tagoloan, Mis. Or.</p>
        </div>

        <!-- Receipt Info -->
        <div class="receipt-details">
            <p><strong>Receipt #:</strong> {{ $transaction->receipt_id }}</p>
            <p><strong>Date:</strong> {{ $transaction->created_at->format('F d, Y h:i A') }}</p>
            <p><strong>Customer:</strong> {{ $transaction->customer->name }}</p>
            <p><strong>Address:</strong> {{ $transaction->customer->address }}</p>
        </div>

        <!-- Items -->
        <div class="receipt-items">
            <table class="table">
                <thead>
                    <tr>
                        <th>Item</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($transaction->items as $item)
                        <tr>
                            <td>{{ $item->product->sku }}</td>
                            <td class="text-end">{{ number_format($item->kilos, 2) }}</td>
                            <td class="text-end">₱{{ number_format($item->price_per_kilo, 2) }}</td>
                            <td class="text-end">₱{{ number_format($item->total, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Totals -->
        <div class="receipt-total">
            <div class="d-flex">
                <span>Subtotal:</span>
                <span>₱{{ number_format($transaction->subtotal, 2) }}</span>
            </div>
            @if ($transaction->discount_amount > 0)
                <div class="d-flex">
                    <span>Discount ({{ $transaction->discount_percentage }}%):</span>
                    <span>₱{{ number_format($transaction->discount_amount, 2) }}</span>
                </div>
            @endif
            <div class="d-flex" style="font-weight: bold;">
                <span>TOTAL:</span>
                <span>₱{{ number_format($transaction->total_amount, 2) }}</span>
            </div>
        </div>

        <!-- Payment Info -->
        <div class="payment-info">
            <div class="d-flex">
                <span>Payment Type:</span>
                <span>{{ ucfirst(str_replace('_', ' ', $transaction->payment_type)) }}</span>
            </div>
            @if ($transaction->payment_type == 'cash')
                <div class="d-flex">
                    <span>Amount Paid:</span>
                    <span>₱{{ number_format($transaction->amount_paid, 2) }}</span>
                </div>
                <div class="d-flex">
                    <span>Change:</span>
                    <span>₱{{ number_format($transaction->change_amount, 2) }}</span>
                </div>
            @endif
        </div>

        <!-- Signatures -->
        <div class="signature-section">
            <div class="signature-line">
                <span>Prepared By</span>
                <br>
                <span>{{ $transaction->prepared_by }}</span>
            </div>
            <div class="signature-line">
                <span>Received By</span>
                <br>
                <span>{{ $transaction->customer->name }}</span>
            </div>
        </div>

        <!-- Footer -->
        <div class="receipt-footer">
            <p>Thank you for your purchase!</p>
            <p>Printed on: {{ now()->format('F d, Y h:i A') }}</p>
            <p>--- End of Receipt ---</p>
        </div>
    </div>

    <script>
        // Auto-print when page loads
        window.onload = function() {
            window.print();
        }
    </script>
</body>

</html>
