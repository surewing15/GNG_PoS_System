<x-app-layout>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <!-- Sales Report Header -->
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Sales Report</h3>
                            </div><!-- .nk-block-head-content -->

                            <div class="nk-block-head-content">
                                <form action="{{ route('sales.report') }}" method="GET" class="d-flex align-items-center">
                                    <div class="me-2">
                                        <label for="date" class="form-label">Select Date</label>
                                        <input type="date" name="date" id="date" class="form-control" value="{{ request()->get('date') }}">
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary mt-4">Filter</button>
                                        <a href="{{ route('sales.report') }}" class="btn btn-secondary mt-4 ms-2">Reset</a>
                                    </div>
                                </form>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->

                    <div class="card card-bordered card-preview">
                        <div class="card-inner">
                            <div class="nk-ck"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                                <canvas class="line-chart chartjs-render-monitor" id="salesChart" width="1836" height="520" style="display: block; width: 918px; height: 260px;"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Sales Transactions Table -->
                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <table class="datatable-init-export nowrap table" data-export-title="Export" data-order="false">
                                    <thead>
                                        <tr>
                                            <th>Receipt ID</th>
                                            <th>Customer</th>
                                            <th>Total Amount</th>
                                            <th>Service Type</th>
                                            <th>Date</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction->receipt_id }}</td>
                                                <td>{{ $transaction->customer->FirstName ?? 'N/A' }} {{ $transaction->customer->LastName ?? 'N/A' }}</td>
                                                <td>
                                                    @if ($transaction->total_amount == 0 || empty($transaction->total_amount))
                                                        ₱ {{ number_format($transaction->subtotal, 2) }}
                                                    @else
                                                        ₱ {{ number_format($transaction->total_amount, 2) }}
                                                    @endif
                                                </td>
                                                <td>{{ $transaction->service_type }}</td>
                                                <td>{{ $transaction->created_at ? $transaction->created_at->format('F j, Y') : 'No date available' }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <em class="icon ni ni-more-h"></em>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <ul class="link-list-opt no-bdr">
                                                                <li><a href="#"><em class="icon ni ni-eye"></em><span>View</span></a></li>
                                                                <li><a href="#"><em class="icon ni ni-trash"></em><span>Delete</span></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .card-preview -->
                    </div><!-- nk-block -->
                </div><!-- nk-content-body -->
            </div><!-- nk-content-inner -->
        </div><!-- container-fluid -->
    </div><!-- nk-content -->

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Prepare the data passed from the controller
        const labels = @json($labels);
        const totalAmountData = @json($totalAmountData);
        const subtotalData = @json($subtotalData);

        // Get the canvas element for the chart
        const ctx = document.getElementById('salesChart').getContext('2d');

        // Create the chart
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: labels, // x-axis labels (dates)
                datasets: [{
                    label: 'Total Amount',
                    data: totalAmountData, // y-axis data for total_amount
                    borderColor: 'rgba(75, 192, 192, 1)',
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    fill: true
                }, {
                    label: 'Subtotal',
                    data: subtotalData, // y-axis data for subtotal
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        title: {
                            display: true,
                            text: 'Date'
                        }
                    },
                    y: {
                        title: {
                            display: true,
                            text: 'Amount (₱)'
                        },
                        ticks: {
                            beginAtZero: true
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
