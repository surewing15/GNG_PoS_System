<div class="modal fade" id="receiptModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="receipt-content text-center">
                    <img src="{{ asset('storage/logo.png') }}" alt="Logo" class="mb-3" style="max-width: 100px">
                    <h4>Thank you for your purchase!</h4>
                    <p>{{ $transaction->customer_name ?? 'Guest' }}</p>
                    <p>{{ $transaction->phone ?? 'N/A' }}</p>

                    <div class="receipt-details text-start my-4">
                        <p><strong>Date:</strong> {{ optional($transaction->date)->format('Y-m-d H:i') ?? now()->format('Y-m-d H:i') }}</p>
                        <p><strong>Receipt #:</strong> {{ $transaction->receipt_id ?? 'TMP'.time() }}</p>
                    </div>

                    <table class="table">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Kilos</th>
                                <th>Price</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($transaction->items ?? [] as $item)
                                <tr>
                                    <td>{{ $item->product->product_sku }}</td>
                                    <td>{{ $item->kilos }}</td>
                                    <td>₱{{ number_format($item->price_per_kilo, 2) }}</td>
                                    <td>₱{{ number_format($item->total, 2) }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4">No items</td>
                                </tr>
                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Total:</strong></td>
                                <td>₱{{ number_format($transaction->total_amount ?? 0, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="printReceipt()">Print</button>
            </div>
        </div>
    </div>
</div>
