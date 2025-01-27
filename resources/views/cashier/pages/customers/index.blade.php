<x-app-layout>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Customers</h3>
                            </div>

                            <div class="nk-block-head-content">
                                <ul class="nk-block-tools g-3">
                                    <li class="nk-block-tools-opt">
                                        <a href="#" class="btn btn-icon btn-primary d-md-none"
                                            data-bs-toggle="modal" data-bs-target="#customerModal">
                                            <em class="icon ni ni-plus"></em>
                                        </a>
                                        <a href="#" class="btn btn-primary d-none d-md-inline-flex"
                                            data-bs-toggle="modal" data-bs-target="#customerModal">
                                            <em class="icon ni ni-plus"></em><span>Add</span>
                                        </a>
                                    </li>
                                </ul>
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"
                                        data-bs-toggle="collapse" data-bs-target="#more-options">
                                        <em class="icon ni ni-more-v"></em>
                                    </a>
                                    <div class="collapse" id="more-options">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <table class="datatable-init-export nowrap table" data-export-title="Export">
                                    <thead>
                                        <tr>
                                            <th>First Name</th>
                                            <th>Last name</th>
                                            <th>Address</th>
                                            <th>Phone</th>
                                            <th>Balance</th>
                                            <th>Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($customers as $customer)
                                            <tr>
                                                <td>{{ $customer->FirstName }}</td>
                                                <td>{{ $customer->LastName }}</td>
                                                <td>{{ $customer->Address }}</td>
                                                <td>{{ $customer->PhoneNumber }}</td>
                                                <td>{{ $customer->Balance }}</td>


                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#"
                                                            class="dropdown-toggle btn btn-icon btn-trigger"
                                                            data-bs-toggle="dropdown">
                                                            <em class="icon ni ni-more-h"></em>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <ul class="link-list-opt no-bdr">
                                                                <li><a href="#"><em
                                                                            class="icon ni ni-edit"></em><span>Edit
                                                                            Customer</span></a></li>
                                                                <li>
                                                                    <a href="javascript:void(0)" class="view-collection"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#viewCollectionModal"
                                                                        onclick="viewCollection(
                                                                       '{{ $customer->CustomerID }}',
                                                                       '{{ $customer->FirstName }}',
                                                                       '{{ $customer->LastName }}',
                                                                       '{{ $customer->Balance }}',
                                                                       '{{ $customer->Collection_ID }}'
                                                                    )">

                                                                        <em
                                                                            class="icon ni ni-eye"></em><span>Collection</span>
                                                                    </a>
                                                                </li>
                                                                <li><a href="#"><em
                                                                            class="icon ni ni-trash"></em><span>Delete
                                                                            Customer</span></a></li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>


                    <div class="modal fade" id="viewCollectionModal">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Manage Payment Collection</h5>
                                    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <em class="icon ni ni-cross"></em>
                                    </a>
                                </div>
                                <div class="modal-body">
                                    <!-- Customer Information Section -->
                                    <div class="card card-bordered card-preview">
                                        <div class="card-inner">
                                            <div class="row g-4">
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label text-muted">Customer Name</label>
                                                        <div class="form-control-wrap">
                                                            <div class="form-control-text customer-name"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                {{-- <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label text-muted">Customer ID</label>
                                                        <div class="form-control-wrap">
                                                            <div class="form-control-text customer-id"></div>
                                                        </div>
                                                    </div>
                                                </div> --}}
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label text-muted">Collection ID</label>
                                                        <div class="form-control-wrap">
                                                            <div class="form-control-text collection-id"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label text-muted">Outstanding Balance</label>
                                                        <div class="form-control-wrap">
                                                            <div class="form-control-text customer-balance"></div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-lg-6">
                                                    <div class="form-group">
                                                        <label class="form-label text-muted">Status</label>
                                                        <div class="form-control-wrap">
                                                            <span class="badge badge-dim payment-status"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment History Section -->
                                    <div class="nk-block mt-4">
                                        <div class="nk-block-head nk-block-head-sm">
                                            <div class="nk-block-between">
                                                <div class="nk-block-head-content">
                                                    <h6 class="nk-block-title">Payment History</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card card-bordered">
                                            <div class="card-inner p-0">
                                                <div class="nk-tb-list nk-tb-ulist" id="paymentHistoryList">
                                                    <div class="nk-tb-item nk-tb-head">
                                                        <div class="nk-tb-col"><span class="sub-text">Date</span>
                                                        </div>
                                                        <div class="nk-tb-col"><span class="sub-text">Amount</span>
                                                        </div>
                                                        <div class="nk-tb-col"><span class="sub-text">Method</span>
                                                        </div>
                                                        <div class="nk-tb-col"><span class="sub-text">Status</span>
                                                        </div>
                                                    </div>
                                                    <div class="loading-placeholder text-center py-4">
                                                        <div class="spinner-border spinner-border-sm text-primary"
                                                            role="status"></div>
                                                        <span class="ms-2">Loading payments...</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Collection Details Section -->
                                    {{-- <div class="nk-block mt-4">
                                        <div class="nk-block-head nk-block-head-sm">
                                            <div class="nk-block-between">
                                                <div class="nk-block-head-content">
                                                    <h6 class="nk-block-title">Collection Details</h6>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="card card-bordered card-preview">
                                            <div class="card-inner">
                                                <div class="row g-4">
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label text-muted">Total Amount</label>
                                                            <div class="form-control-wrap">
                                                                <div class="form-control-text">₱
                                                                    645.00</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <div class="form-group">
                                                            <label class="form-label text-muted">Due Date</label>
                                                            <div class="form-control-wrap">
                                                                <div class="form-control-text">05/09/25</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div> --}}
                                </div>
                                <div class="modal-footer bg-light">
                                    <div class="row w-100">
                                        <div class="col text-end">
                                            <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary"
                                                onclick="showPaymentModal()">
                                                <em class="icon ni ni-plus"></em>
                                                <span>Make Payment</span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="acceptPaymentModal">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Accept Payment</h5>
                                    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                                        <em class="icon ni ni-cross"></em>
                                    </a>
                                </div>

                                <form action="{{ route('payments.storePayment') }}" method="POST" id="paymentForm">
                                    @csrf
                                    <input type="hidden" name="customer_id" id="payment_customer_id">

                                    <div class="modal-body">
                                        <div class="nk-block">
                                            <div class="row g-4">
                                                <div class="col-12">
                                                    <div class="card card-bordered">
                                                        <div class="card-inner">
                                                            <div class="row g-3">
                                                                <div class="col-6">
                                                                    <span class="sub-text">Customer Name</span>
                                                                    <span class="lead-text"
                                                                        id="payment_customer_name"></span>
                                                                </div>
                                                                <div class="col-6">
                                                                    <span class="sub-text">Outstanding Balance</span>
                                                                    <span class="lead-text"
                                                                        id="payment_customer_balance"></span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="payment-amount">Payment
                                                            Amount</label>
                                                        <div class="form-control-wrap">
                                                            <div class="form-icon form-icon-left">
                                                                <em class="icon">₱</em>
                                                            </div>
                                                            <input type="number" class="form-control" name="amount"
                                                                id="payment-amount" placeholder="0.00" step="0.01"
                                                                required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <select class="form-select js-select2" name="payment_method"
                                                            id="payment-method">
                                                            <option value="cash">Cash</option>
                                                            <option value="check">Check</option>
                                                            <option value="bank">Bank Transfer</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="col-12" id="check-fields" style="display: none;">
                                                    <div class="row g-3">
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Check Number</label>
                                                                <input type="text" class="form-control"
                                                                    name="check_number"
                                                                    placeholder="Enter check number">
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Bank Name</label>
                                                                <input type="text" class="form-control"
                                                                    name="check_bank_name"
                                                                    placeholder="Enter bank name">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12" id="bank-fields" style="display: none;">
                                                    <div class="row g-3">
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Bank Number</label>
                                                                <input type="text" class="form-control"
                                                                    name="bank_number"
                                                                    placeholder="Enter bank number">
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="form-group">
                                                                <label class="form-label">Bank Name</label>
                                                                <input type="text" class="form-control"
                                                                    name="bank_name" placeholder="Enter bank name">
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="payment-date">Payment
                                                            Date</label>
                                                        <div class="form-control-wrap">
                                                            <div class="form-icon form-icon-left">
                                                                <em class="icon ni ni-calendar"></em>
                                                            </div>
                                                            <input type="text" class="form-control date-picker"
                                                                name="payment_date" id="payment-date" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="col-12">
                                                    <div class="form-group">
                                                        <label class="form-label" for="payment-notes">Notes</label>
                                                        <div class="form-control-wrap">
                                                            <textarea class="form-control" name="notes" id="payment-notes" rows="3"
                                                                placeholder="Enter any additional notes"></textarea>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer bg-light">
                                        <div class="row g-3 align-items-center w-100">
                                            <div class="col">
                                                <div class="note note-warning">
                                                    <em class="icon ni ni-info-fill"></em>
                                                    <span>Payment will be marked as pending until processed</span>
                                                </div>
                                            </div>
                                            <div class="col-auto">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Cancel</button>
                                                <button type="submit" class="btn btn-primary">Accept Payment</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('cashier.modal.customer-modal')

    <script>
        let currentCustomerId = null;

        function viewCollection(id, firstName, lastName, balance, collectionId) {
            currentCustomerId = id;
            // Create collection ID from customer ID
            const modal = $('#viewCollectionModal');

            updateModalInfo(firstName, lastName, id, balance, collectionId);
            resetPaymentList();
            modal.modal('show');

            fetchPaymentHistory(id);
        }


        function updateModalInfo(firstName, lastName, id, balance, collectionId) {
            const modal = $('#viewCollectionModal');
            modal.find('.customer-name').text(`${firstName} ${lastName}`);
            modal.find('.customer-id').text(id);
            modal.find('.collection-id').text(collectionId);
            modal.find('.customer-balance').text(`₱${parseFloat(balance).toFixed(2)}`);
            modal.find('.payment-status').text(balance > 0 ? 'Pending' : 'Paid')
                .removeClass('bg-warning bg-success')
                .addClass(balance > 0 ? 'bg-warning' : 'bg-success');
        }

        function resetPaymentList() {
            const paymentList = $('#paymentHistoryList');
            paymentList.find('.nk-tb-item:not(.nk-tb-head)').remove();
            paymentList.append(`
                <div class="loading-placeholder text-center py-4">
                    <div class="spinner-border spinner-border-sm text-primary" role="status"></div>
                    <span class="ms-2">Loading payments...</span>
                </div>
            `);
        }

        function fetchPaymentHistory(id) {
            fetch(`/cashier/collection/${id}`)
                .then(response => response.ok ? response.json() : Promise.reject('Network response failed'))
                .then(data => updatePaymentList(data.payments))
                .catch(showError);
        }

        function updatePaymentList(payments) {
            const paymentList = $('#paymentHistoryList');
            paymentList.find('.loading-placeholder').remove();

            if (!payments.length) {
                paymentList.append(`
                    <div class="nk-tb-item">
                        <div class="nk-tb-col text-center" colspan="4">
                            <em class="icon ni ni-info"></em>
                            <span class="ms-2">No payment records found</span>
                        </div>
                    </div>
                `);
                return;
            }

            payments.forEach(payment => paymentList.append(createPaymentRow(payment)));
        }

        function createPaymentRow(payment) {
            const date = new Date(payment.payment_date).toLocaleDateString();
            const amount = parseFloat(payment.amount).toFixed(2);
            const method = payment.payment_method.charAt(0).toUpperCase() + payment.payment_method.slice(1);

            return `
                <div class="nk-tb-item">
                    <div class="nk-tb-col"><span>${date}</span></div>
                    <div class="nk-tb-col"><span>₱${amount}</span></div>
                    <div class="nk-tb-col"><span>${method}</span></div>
                    <div class="nk-tb-col">
                        <span class="badge badge-dim bg-success">Paid</span>
                    </div>
                </div>
            `;
        }

        function showPaymentModal() {
            // Store current customer info
            const currentCustomer = {
                id: currentCustomerId,
                name: $('#viewCollectionModal .customer-name').text(),
                balance: $('#viewCollectionModal .customer-balance').text()
            };

            // Update payment form
            $('#payment_customer_id').val(currentCustomer.id);
            $('#payment_customer_name').text(currentCustomer.name);
            $('#payment_customer_balance').text(currentCustomer.balance);

            // Reset form fields
            $('#paymentForm')[0].reset();
            $('#payment-method').val('cash').trigger('change');
            $('#payment-date').datepicker('setDate', new Date());

            // Switch modals
            $('#viewCollectionModal').modal('hide');
            $('#acceptPaymentModal').modal('show');
        }

        // Add modal handlers
        $('#acceptPaymentModal').on('hidden.bs.modal', () => {
            $('#viewCollectionModal').modal('show');
        });

        function showError() {
            $('#paymentHistoryList').find('.loading-placeholder')
                .html(`
                    <div class="text-danger">
                        <em class="icon ni ni-alert-circle"></em>
                        <span class="ms-2">Failed to load payment history</span>
                    </div>
                `);
        }

        $('#viewCollectionModal').on('hidden.bs.modal', () => {
            currentCustomerId = null;
        });
    </script>
</x-app-layout>
