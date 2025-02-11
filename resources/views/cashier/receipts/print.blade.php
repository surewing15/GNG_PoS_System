<!-- resources/views/cashier/receipts/print.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Receipt #{{ $transaction->receipt_id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        .receipt {
            width: 80mm;
            margin: 0 auto;
        }
        .receipt-header {
            text-align: center;
            margin-bottom: 20px;
        }
        .receipt-logo {
            max-width: 100px;
            margin-bottom: 10px;
        }
        .receipt-details {
            margin-bottom: 20px;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .items-table th, .items-table td {
            text-align: right;
            padding: 5px;
        }
        .items-table th:first-child, .items-table td:first-child {
            text-align: left;
        }
        .totals {
            text-align: right;
            margin-bottom: 20px;
        }
        .footer {
            text-align: center;
            font-size: 10px;
            margin-top: 20px;
        }
        @media print {
            body { margin: 0; padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>
    <div class="receipt">
        <div class="receipt-header">
            <img src="{{ asset('storage/logo.png') }}" alt="Logo" class="receipt-logo">
            <h2>Receipt</h2>
            <p>Thank you for your purchase!</p>
        </div>

        <div class="receipt-details">
            <p><strong>Receipt #:</strong> {{ $transaction->receipt_id }}</p>
            <p><strong>Date:</strong> {{ $transaction->created_at->format('M d, Y h:i A') }}</p>
            <p><strong>Customer:</strong> {{ $transaction->customer->FirstName }} {{ $transaction->customer->LastName }}</p>
            <p><strong>Service Type:</strong> {{ ucfirst($transaction->service_type) }}</p>
            <p><strong>Payment Type:</strong> {{ ucfirst($transaction->payment_type) }}</p>
        </div>

        <table class="items-table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Kilos</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transaction->items as $item)
                <tr>
                    <td>{{ $item->product->product_sku }}</td>
                    <td>{{ number_format($item->kilos, 2) }}</td>
                    <td>₱{{ number_format($item->price_per_kilo, 2) }}</td>
                    <td>₱{{ number_format($item->total, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="totals">
            <p><strong>Subtotal:</strong> ₱{{ number_format($transaction->subtotal, 2) }}</p>
            @if($transaction->discount_amount > 0)
            <p><strong>Discount ({{ $transaction->discount_percentage }}%):</strong> ₱{{ number_format($transaction->discount_amount, 2) }}</p>
            @endif
            <p><strong>Total Amount:</strong> ₱{{ number_format($transaction->total_amount, 2) }}</p>
            <p><strong>Amount Paid:</strong> ₱{{ number_format($transaction->amount_paid, 2) }}</p>
            <p><strong>Change:</strong> ₱{{ number_format($transaction->change_amount, 2) }}</p>
            @if($transaction->remaining_balance > 0)
            <p><strong>Remaining Balance:</strong> ₱{{ number_format($transaction->remaining_balance, 2) }}</p>
            @endif
        </div>

        <div class="footer">
            <p>This receipt was generated electronically and is valid without signature.</p>
        </div>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
