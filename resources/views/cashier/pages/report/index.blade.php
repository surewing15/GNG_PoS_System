<x-app-layout>
    <div class="container-fluid mt-3">

        <div class="nk-block-head nk-block-head-sm mb-4">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Daily Sales Report</h3>
                    <div class="nk-block-des text-soft">
                        <p>Detailed overview of daily transactions</p>
                    </div>
                </div>
                <div class="nk-block-head-content">
                    <div class="toggle-wrap nk-block-tools-toggle">
                        <div class="toggle-expand-content">
                            <ul class="nk-block-tools g-3">
                                <li>
                                    <div class="form-control-wrap">
                                        <div class="form-icon form-icon-right">
                                            <em class="icon ni ni-calendar"></em>
                                        </div>
                                        <input type="text" class="form-control date-picker" id="datepicker"
                                            placeholder="dd/mm/yyyy">
                                    </div>
                                </li>
                                <li class="nk-block-tools-opt">
                                    <button onclick="exportReport()" class="btn btn-primary">
                                        <em class="icon ni ni-download-cloud"></em>
                                        <span>Export to Excel</span>
                                    </button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="/cashier/report" class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">Start Date</label>
                        <input type="date" class="form-control" name="start_date" value="{{ $startDateFormatted }}">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">End Date</label>
                        <input type="date" class="form-control" name="end_date" value="{{ $endDateFormatted }}">
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Payment Type</label>
                        <select class="form-select" name="payment_type">
                            <option value="">All Types</option>
                            <option value="cash" {{ $paymentType == 'cash' ? 'selected' : '' }}>Cash</option>
                            <option value="debit" {{ $paymentType == 'debit' ? 'selected' : '' }}>Debit</option>
                            <option value="online" {{ $paymentType == 'online' ? 'selected' : '' }}>Online</option>
                        </select>
                    </div>
                    <div class="col-md-1 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </form>
            </div>
        </div>


        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <h6 class="card-title">Total Items Sold</h6>
                        <h3>{{ $totalItems }} items</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="card-title">Total Weight Sold</h6>
                        <h3>{{ number_format($totalKilos, 2) }} kg</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="card-title">Total Sales</h6>
                        <h3>₱{{ number_format($totalSales, 2) }}</h3>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <h6 class="card-title">Average Price/Kilo</h6>
                        <h3>₱{{ number_format($averagePricePerKilo, 2) }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transactions Table -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Transaction Items History</h5>
                <div class="table-responsive">
                    <table class="table table-striped table-bordered" id="transactionItemsTable">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Transaction ID</th>
                                <th>Product SKU</th>
                                <th>Kilos</th>
                                <th>Price/Kilo</th>
                                <th>Total</th>
                                <th>Customer</th>
                                <th>Payment Type</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactionItems as $item)
                                <tr>
                                    <td>{{ Carbon\Carbon::parse($item->date)->format('M d, Y ') }}</td>
                                    <td>{{ $item->transaction->receipt_id }}</td>
                                    <td>{{ $item->product->product_sku }}</td>
                                    <td>{{ number_format($item->kilos, 2) }}</td>
                                    <td>₱{{ number_format($item->price_per_kilo, 2) }}</td>
                                    <td>
                                        @if ($item->transaction->payment_type === 'advance_payment')
                                            ₱{{ number_format($item->transaction->amount_paid, 2) }}
                                        @else
                                            ₱{{ number_format($item->total, 2) }}
                                        @endif
                                    </td>
                                    <td>{{ optional($item->transaction->customer)->FirstName }}
                                        {{ optional($item->transaction->customer)->LastName }}</td>
                                    <td>
                                        <span
                                            class="badge bg-{{ $item->transaction->payment_type === 'cash' ? 'success' : ($item->transaction->payment_type === 'debit' ? 'warning' : 'info') }}">
                                            {{ ucfirst($item->transaction->payment_type) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>


    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#transactionItemsTable').DataTable({
                order: [
                    [0, 'desc']
                ],
                pageLength: 25,
                language: {
                    search: "Search records:"
                }
            });

            // Initialize Datepicker with proper format
            $('.date-picker').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                // Add an onSelect event handler
                onSelect: function(dateStr) {
                    // Update the corresponding form input
                    $('input[name="start_date"]').val(dateStr);
                    $('input[name="end_date"]').val(dateStr);
                }
            });
        });

        function exportReport() {
            // Get the filter values
            let startDate = document.querySelector('input[name="start_date"]').value;
            let endDate = document.querySelector('input[name="end_date"]').value;
            const paymentType = document.querySelector('select[name="payment_type"]').value;

            // If using the single datepicker, get its value
            const datepickerValue = document.getElementById('datepicker').value;
            if (datepickerValue) {
                startDate = datepickerValue;
                endDate = datepickerValue;
            }

            // Validate dates
            if (!startDate || !endDate) {
                alert('Please select both start and end dates');
                return;
            }

            // Build the export URL with proper encoding
            const params = new URLSearchParams({
                start_date: startDate,
                end_date: endDate,
                payment_type: paymentType
            });

            // Redirect to export endpoint
            window.location.href = `/cashier/report/export?${params.toString()}`;
        }
    </script>
</x-app-layout>
