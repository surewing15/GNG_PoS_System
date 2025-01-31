<x-app-layout>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Expenses Report</h3>
                            </div><!-- .nk-block-head-content -->

                            <div class="nk-block-head-content">
                                <form action="{{ route('expenses.report') }}" method="GET"
                                    class="d-flex align-items-center">
                                    <div class="me-2">
                                        <label for="date" class="form-label">Select Date</label>
                                        <input type="date" name="date" id="date" class="form-control"
                                            value="{{ request()->get('date') }}">
                                    </div>
                                    <div>
                                        <button type="submit" class="btn btn-primary mt-4">Filter</button>
                                        <a href="{{ route('expenses.report') }}"
                                            class="btn btn-secondary mt-4 ms-2">Reset</a>
                                    </div>
                                </form>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->

                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <table class="datatable-init-export nowrap table" data-export-title="Export"
                                    data-order="false">
                                    <thead>
                                        <tr>
                                            <th>Withdraw By</th>
                                            <th>Receive By</th>
                                            <th>Description</th>
                                            <th>Amount</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($expenses as $expense)
                                            <tr>
                                                <td>{{ $expense->e_withdraw_by }}</td>
                                                <td>{{ $expense->e_recieve_by }}</td>
                                                <td>{{ $expense->e_description }}</td>
                                                <td>₱ {{ number_format($expense->e_amount, 2) }}</td>
                                                <td>{{ $expense->created_at->format('F j') }}</td>
                                                <td>{{ $expense->created_at->format('h:i A') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .card-preview -->
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
