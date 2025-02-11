<x-app-layout>
    <div class="container-fluid mt-3">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Denomination Count</h3>
                    <div class="nk-block-des text-soft">
                        <p>Daily cash and online payment counting</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <form id="denominationForm" class="row g-3">
                    @csrf
                    <input type="hidden" name="count_date" value="{{ now()->format('Y-m-d') }}">

                    <!-- Cash Denominations Section -->
                    <div class="col-12">
                        <h6 class="text-primary fw-bold">Cash Denominations</h6>
                        <hr>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">₱1000 x</label>
                                <input type="number" class="form-control denomination-input" name="d1000"
                                    value="{{ $denomination->d1000 ?? 0 }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">₱500 x</label>
                                <input type="number" class="form-control denomination-input" name="d500"
                                    value="{{ $denomination->d500 ?? 0 }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">₱200 x</label>
                                <input type="number" class="form-control denomination-input" name="d200"
                                    value="{{ $denomination->d200 ?? 0 }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">₱100 x</label>
                                <input type="number" class="form-control denomination-input" name="d100"
                                    value="{{ $denomination->d100 ?? 0 }}" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">₱50 x</label>
                                <input type="number" class="form-control denomination-input" name="d50"
                                    value="{{ $denomination->d50 ?? 0 }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">₱20 x</label>
                                <input type="number" class="form-control denomination-input" name="d20"
                                    value="{{ $denomination->d20 ?? 0 }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">₱10 x</label>
                                <input type="number" class="form-control denomination-input" name="d10"
                                    value="{{ $denomination->d10 ?? 0 }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">₱5 x</label>
                                <input type="number" class="form-control denomination-input" name="d5"
                                    value="{{ $denomination->d5 ?? 0 }}" min="0">
                            </div>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">₱1 x</label>
                                <input type="number" class="form-control denomination-input" name="d1"
                                    value="{{ $denomination->d1 ?? 0 }}" min="0">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label class="form-label">₱0.25 x</label>
                                <input type="number" class="form-control denomination-input" name="d025"
                                    value="{{ $denomination->d025 ?? 0 }}" min="0">
                            </div>
                        </div>
                    </div>

                    <!-- Online Payment Section -->
                    <div class="col-12 mt-4">
                        <h6 class="text-primary fw-bold">Online Payments</h6>
                        <hr>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Online Payment Amount</label>
                            <div class="input-group">
                                <span class="input-group-text">₱</span>
                                <input type="number" class="form-control" name="online_amount"
                                    value="{{ $denomination->online_amount ?? 0 }}" step="0.01" min="0">
                            </div>
                        </div>
                    </div>

                    <!-- Summary Section -->
                    <div class="col-12 mt-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="card bg-primary text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Cash Total</h6>
                                        <h3>₱<span id="cashTotal">0.00</span></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-success text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Online Total</h6>
                                        <h3>₱<span id="onlineTotal">0.00</span></h3>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="card bg-info text-white">
                                    <div class="card-body">
                                        <h6 class="card-title">Grand Total</h6>
                                        <h3>₱<span id="grandTotal">0.00</span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary">
                            <em class="icon ni ni-save"></em>
                            <span>Save Count</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            // Add CSRF token to all AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Function to calculate totals
            function calculateTotals() {
                let cashTotal = 0;
                cashTotal += parseInt($('input[name="d1000"]').val() || 0) * 1000;
                cashTotal += parseInt($('input[name="d500"]').val() || 0) * 500;
                cashTotal += parseInt($('input[name="d200"]').val() || 0) * 200;
                cashTotal += parseInt($('input[name="d100"]').val() || 0) * 100;
                cashTotal += parseInt($('input[name="d50"]').val() || 0) * 50;
                cashTotal += parseInt($('input[name="d20"]').val() || 0) * 20;
                cashTotal += parseInt($('input[name="d10"]').val() || 0) * 10;
                cashTotal += parseInt($('input[name="d5"]').val() || 0) * 5;
                cashTotal += parseInt($('input[name="d1"]').val() || 0);
                cashTotal += parseInt($('input[name="d025"]').val() || 0) * 0.25;

                let onlineTotal = parseFloat($('input[name="online_amount"]').val() || 0);
                let grandTotal = cashTotal + onlineTotal;

                $('#cashTotal').text(cashTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                $('#onlineTotal').text(onlineTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
                $('#grandTotal').text(grandTotal.toLocaleString('en-US', {
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
            }

            // Calculate on input change
            $('.denomination-input, input[name="online_amount"]').on('input', function() {
                calculateTotals();
            });

            // Handle form submission
            $('#denominationForm').on('submit', function(e) {
                e.preventDefault();

                $.ajax({
                    url: "/cashier/denomination/store",
                    method: 'POST',
                    data: $(this).serialize(),
                    beforeSend: function() {
                        $('button[type="submit"]').prop('disabled', true).html(
                            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...'
                        );
                    },
                    success: function(response) {
                        if (response.success) {
                            toastr.success('Count saved successfully');
                            calculateTotals();
                        } else {
                            toastr.error(response.message || 'Error saving count');
                        }
                    },
                    error: function(xhr, status, error) {
                        if (xhr.status === 422) {
                            const errors = xhr.responseJSON.errors;
                            Object.keys(errors).forEach(field => {
                                toastr.error(errors[field][0]);
                            });
                        } else {
                            toastr.error('Error saving count. Please try again.');
                        }
                    },
                    complete: function() {
                        $('button[type="submit"]').prop('disabled', false).html(
                            '<em class="icon ni ni-save"></em><span>Save Count</span>'
                        );
                    }
                });
            });

            // Calculate initial totals
            calculateTotals();
        });
    </script>
</x-app-layout>
