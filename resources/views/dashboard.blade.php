<x-app-layout>
    @if (Auth::user()->role == 'Administrator')
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <div class="nk-block-head nk-block-head-sm">
                    <div class="nk-block-between">
                        <div class="nk-block-head-content">
                            <h3 class="nk-block-title page-title">Dashboard</h3>
                        </div>
                    </div>
                </div>

                <div class="nk-block">
                    <div class="row g-gs">
                        <div class="col-xxl-9">
                            <div class="row g-gs">
                                <!-- Weekly Sales Chart -->
                                <div class="col-lg-12">
                                    <div class="card card-bordered h-100">
                                        <div class="card-inner">
                                            <div class="card-title-group mb-3">
                                                <div class="card-title">
                                                    <h6>Weekly Sales Overview</h6>
                                                </div>
                                            </div>
                                            <div style="height: 400px;">
                                                <canvas id="salesBarChart"></canvas>
                                            </div>
                                            <div class="d-flex justify-content-between mt-3">
                                                <div>
                                                    <span>This Week</span>
                                                    <h6>₱{{ number_format($salesData['this_week_sales'], 2) }}</h6>
                                                </div>
                                                <div class="text-end">
                                                    <span>Growth</span>
                                                    <div class="d-flex align-items-center">
                                                        <i class="bi bi-arrow-up me-1"></i>
                                                        <h6 class="mb-0">
                                                            {{ number_format($salesData['percentage_change'], 2) }}%
                                                        </h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Summary & Expenses Section -->
                                <!-- Summary Stats -->
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-inner">
                                            <h6 class="title mb-4">Summary</h6>
                                            <ul class="nk-store-statistics">
                                                <li class="item mb-4">
                                                    <div class="info">
                                                        <div class="title">Total Sales</div>
                                                        <div class="count">₱{{ number_format($totalSales, 2) }}</div>
                                                    </div>
                                                    <em class="icon bg-purple-dim ni ni-growth"></em>
                                                </li>
                                                <li class="item mb-4">
                                                    <div class="info">
                                                        <div class="title">Total Expenses</div>
                                                        <div class="count">₱{{ number_format($totalExpenses, 2) }}
                                                        </div>
                                                    </div>
                                                    <em class="icon bg-primary-dim ni ni-bag"></em>
                                                </li>
                                                <li class="item mb-4">
                                                    <div class="info">
                                                        <div class="title">Total Cash</div>
                                                        <div class="count">₱{{ number_format($totalCashSales, 2) }}
                                                        </div>
                                                    </div>
                                                    <em class="icon bg-purple-dim ni ni-money"></em>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>

                                <!-- Expenses List -->
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-inner">
                                            <h6 class="title mb-4">Expenses</h6>
                                            <div class="table-responsive" style="max-height: 300px; overflow-y: auto;">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Description</th>
                                                            <th class="text-end">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($expenses as $expense)
                                                            <tr class="mb-3">
                                                                <td class="py-3">{{ $expense->e_description }}</td>
                                                                <td class="text-end py-3">
                                                                    ₱{{ number_format($expense->e_amount, 2) }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <!-- Customers Card -->
                                <div class="col-md-4">
                                    <div class="card h-100">
                                        <div class="card-inner">
                                            <div class="d-flex justify-content-between mb-3">
                                                <h6 class="title">Total Customers</h6>
                                                <span class="bg-success-soft px-2 py-1 rounded">Active</span>
                                            </div>
                                            <div class="amount display-6 mb-3">{{ $totalCustomerCount }}</div>
                                            <div class="chart-container" style="height: 100px;">
                                                <canvas id="customerTrendChart"></canvas>
                                            </div>
                                            <div class="d-flex justify-content-between align-items-center mt-3">
                                                <div>
                                                    <span class="d-block text-muted small">Weekly Growth</span>
                                                    <span class="fw-bold">{{ $percentageChange }}%</span>
                                                </div>
                                                <div
                                                    class="change-indicator {{ $percentageChange > 0 ? 'text-success' : 'text-danger' }}">
                                                    <em
                                                        class="icon ni ni-arrow-long-{{ $percentageChange > 0 ? 'up' : 'down' }}"></em>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Transactions Section -->
                        <div class="col-12">
                            <div class="card h-100">
                                <div class="card-inner">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a class="nav-link active" data-bs-toggle="tab" href="#debits">Debits</a>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" data-bs-toggle="tab" href="#sales">Sales</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content mt-3">
                                        <!-- Debits Tab -->
                                        <div class="tab-pane fade show active" id="debits">
                                            <div class="d-flex justify-content-end mb-3">
                                                <a href="{{ route('export', 'debits') }}"
                                                    class="btn btn-primary">Export Debits</a>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Customer</th>
                                                            <th>Series #</th>
                                                            <th class="text-end">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($debits as $transaction)
                                                            <tr>
                                                                <td>{{ $transaction->FirstName }}
                                                                    {{ $transaction->LastName }}</td>
                                                                <td>{{ $transaction->receipt_id }}</td>
                                                                <td class="text-end">
                                                                    ₱{{ number_format($transaction->final_amount, 2) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <!-- Sales Tab -->
                                        <div class="tab-pane fade" id="sales">
                                            <div class="d-flex justify-content-end mb-3">
                                                <a href="{{ route('export', 'sales') }}" class="btn btn-primary">Export
                                                    Sales</a>
                                            </div>
                                            <div class="table-responsive">
                                                <table class="table table-hover">
                                                    <thead>
                                                        <tr>
                                                            <th>Customer</th>
                                                            <th>Series #</th>
                                                            <th class="text-end">Amount</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($customers as $customer)
                                                            <tr>
                                                                <td>{{ $customer->FirstName }}
                                                                    {{ $customer->LastName }}</td>
                                                                <td>{{ $customer->receipt_id ?? 'N/A' }}</td>
                                                                <td class="text-end">
                                                                    ₱{{ number_format($customer->final_amount ?? 0, 2) }}
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const salesData = @json($weeklySales);
            const ctx = document.getElementById('salesBarChart');

            if (!ctx) return;

            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: salesData.map(d => d.name),
                    datasets: [{
                        label: 'Cash Sales',
                        data: salesData.map(d => d.cash_sales),
                        backgroundColor: '#6366f1',
                        borderRadius: 4
                    }, {
                        label: 'Debit Sales',
                        data: salesData.map(d => d.debit_sales),
                        backgroundColor: '#818cf8',
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => '₱' + value.toLocaleString()
                            }
                        }
                    }
                }
            });

            const ctxCustomer = document.getElementById('customerTrendChart').getContext('2d');
            new Chart(ctxCustomer, {
                type: 'line',
                data: {
                    labels: ['', '', '', '', '', ''],
                    datasets: [{
                        data: [65, 59, 80, 81, 56, {{ $totalCustomerCount }}],
                        borderColor: '#1ee0ac',
                        borderWidth: 2,
                        fill: true,
                        backgroundColor: 'rgba(30, 224, 172, 0.1)',
                        tension: 0.4,
                        pointRadius: 0
                    }]
                },
                options: {
                    plugins: {
                        legend: {
                            display: false
                        }
                    },
                    scales: {
                        x: {
                            display: false
                        },
                        y: {
                            display: false
                        }
                    }
                }
            });
        });
    </script>
</x-app-layout>
