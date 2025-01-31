<x-app-layout>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Expenses</h3>
                            </div><!-- .nk-block-head-content -->

                            <div class="nk-block-head-content">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
                                        <!-- Buttons to trigger the modal -->
                                        <a href="#" class="btn btn-icon btn-primary d-md-none"
                                            data-bs-toggle="modal" data-bs-target="#expensesModal">
                                            <em class="icon ni ni-plus"></em>
                                        </a>
                                        <a href="#" class="btn btn-primary d-none d-md-inline-flex"
                                            data-bs-toggle="modal" data-bs-target="#expensesModal">
                                            <em class="icon ni ni-plus"></em><span>Create Expenses</span>
                                        </a>
                                        @include('admin.forms.expenses-modal')
                                    </li>
                                </ul>
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"
                                        data-bs-toggle="collapse" data-bs-target="#more-options">
                                        <em class="icon ni ni-more-v"></em>
                                    </a>
                                    <div class="collapse" id="more-options">
                                        <!-- Additional options can be added here -->
                                    </div>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->

                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <table class="datatable-init-export nowrap table" data-export-title="Export">
                                    <thead>
                                        <tr>
                                            <th>Withdraw By</th>
                                            <th>Receive By</th>
                                            <th>Description</th>
                                            <th>Amount</th>
                                            <th>Return Amount</th>
                                            <th>Return By</th>
                                            <th>Return Date</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($expenses as $expense)
                                            <tr>
                                                <td>{{ $expense->e_withdraw_by }}</td>
                                                <td>{{ $expense->e_recieve_by }}</td>
                                                <td>{{ $expense->e_description }}</td>
                                                <td>{{ number_format($expense->e_amount, 2) }}</td>
                                                <td>{{ $expense->e_return_amount ? number_format($expense->e_return_amount, 2) : '-' }}
                                                </td>
                                                <td>{{ $expense->e_return_by ?? '-' }}</td>
                                                <td>{{ $expense->e_return_date ? $expense->e_return_date->format('F j, Y') : '-' }}
                                                </td>

                                                </td>
                                                <td>
                                                    @if (!$expense->e_return_amount)
                                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal"
                                                            data-bs-target="#returnModal{{ $expense->id }}">
                                                            Return Cash
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>

                                <!-- Return Cash Modal -->
                                @foreach ($expenses as $expense)
                                    <div class="modal fade" id="returnModal{{ $expense->id }}" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Return Cash for Expense</h5>
                                                    <button type="button" class="btn-close"
                                                        data-bs-dismiss="modal"></button>
                                                </div>
                                                <form action="{{ route('expenses.return', $expense->id) }}"
                                                    method="POST">
                                                    @csrf
                                                    <div class="modal-body">
                                                        <div class="form-group">
                                                            <label>Return Amount</label>
                                                            <input type="number" name="return_amount"
                                                                class="form-control" max="{{ $expense->e_amount }}"
                                                                step="0.01" required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Return By</label>
                                                            <input type="text" name="return_by" class="form-control"
                                                                required>
                                                        </div>
                                                        <div class="form-group">
                                                            <label>Return Description</label>
                                                            <textarea name="return_description" class="form-control" required></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Submit
                                                            Return</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div><!-- .card-preview -->
                    </div> <!-- nk-block -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
