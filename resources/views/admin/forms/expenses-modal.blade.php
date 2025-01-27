<!-- Modal -->
<div class="modal fade" id="expensesModal" tabindex="-1" role="dialog" aria-labelledby="expensesModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="expenses">Create Expenses</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('expenses.store') }}" method="POST">
                @csrf <!-- CSRF token for security -->
                <div class="modal-body">
                    <div class="form-group">
                        <label for="withdraw_by">Withdraw By <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="withdraw_by" name="withdraw_by"
                            required>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="recieve_by">Recieve By <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="recieve_by" name="recieve_by"
                            required>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="expenses_description">Description <span class="text-danger">*</span></label>
                        <input class="form-control" type="text" id="expenses_description" name="expenses_description"
                            required>
                    </div>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="expenses_amount">Amount <span class="text-danger">*</span></label>
                        <input class="form-control" type="number" id="expenses_amount" name="expenses_amount"
                            step="0.01" min="0" required>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create <i class="bi bi-check"></i></button>
                </div>
            </form>
        </div>
    </div>
</div>
