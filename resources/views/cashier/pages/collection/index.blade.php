<x-app-layout>

    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <!-- Page Header -->
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Collection Management</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Manage your payment collections</p>
                                </div>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <button class="btn btn-icon btn-trigger" data-target="pageMenu">
                                        <em class="icon ni ni-more-v"></em>
                                    </button>
                                    <div class="toggle-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li>
                                                <div class="drodown">
                                                    <a href="#" class="btn btn-white btn-dim btn-outline-light">
                                                        <em class="d-none d-sm-inline icon ni ni-download-cloud"></em>
                                                        <span>Export</span>
                                                    </a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Search Form -->
                    <div class="card">
                        <div class="card-inner">
                            <div class="row g-3 align-center">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-left">
                                                <em class="icon ni ni-search"></em>
                                            </div>
                                            <input type="text" class="form-control" placeholder="Search by name...">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <select class="form-select js-select2">
                                                <option value="">Select Type</option>
                                                <option value="pending">Pending</option>
                                                <option value="paid">Paid</option>
                                                <option value="overdue">Overdue</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="form-control-wrap">
                                            <div class="form-icon form-icon-left">
                                                <em class="icon ni ni-calendar"></em>
                                            </div>
                                            <input type="text" class="form-control date-picker"
                                                placeholder="Date Range">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-3">
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="check" name="paymentType"
                                        class="custom-control-input">
                                    <label class="custom-control-label" for="check">Check</label>
                                </div>
                                <div class="custom-control custom-radio custom-control-inline">
                                    <input type="radio" id="creditCard" name="paymentType"
                                        class="custom-control-input">
                                    <label class="custom-control-label" for="creditCard">Credit Card</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="card card-bordered card-stretch">
                        <div class="card-inner-group">
                            <div class="card-inner position-relative card-tools-toggle">
                                <div class="card-title-group">
                                    <div class="card-tools">
                                        <div class="form-inline flex-nowrap gx-3">
                                            <div class="form-wrap w-150px">
                                                <select class="form-select js-select2">
                                                    <option value="">Bulk Action</option>
                                                    <option value="paid">Mark as Paid</option>
                                                    <option value="delete">Delete</option>
                                                </select>
                                            </div>
                                            <div class="btn-wrap">
                                                <span class="d-none d-md-block"><button
                                                        class="btn btn-dim btn-outline-light">Apply</button></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-tools me-n1">
                                        <ul class="btn-toolbar gx-1">
                                            <li>
                                                <div class="toggle-wrap">
                                                    <a href="#" class="btn btn-icon btn-trigger toggle"
                                                        data-target="cardTools">
                                                        <em class="icon ni ni-menu-right"></em>
                                                    </a>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div class="card-inner p-0">
                                <div class="nk-tb-list nk-tb-ulist">
                                    <div class="nk-tb-item nk-tb-head">
                                        <div class="nk-tb-col nk-tb-col-check">
                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                <input type="checkbox" class="custom-control-input" id="selectAll">
                                                <label class="custom-control-label" for="selectAll"></label>
                                            </div>
                                        </div>
                                        <div class="nk-tb-col"><span class="sub-text">ID</span></div>
                                        <div class="nk-tb-col"><span class="sub-text">Date</span></div>
                                        <div class="nk-tb-col"><span class="sub-text">Amount</span></div>
                                        <div class="nk-tb-col"><span class="sub-text">Payment</span></div>
                                        <div class="nk-tb-col"><span class="sub-text">Balance</span></div>
                                        <div class="nk-tb-col"><span class="sub-text">Due Date</span></div>
                                        <div class="nk-tb-col"><span class="sub-text">Status</span></div>
                                        <div class="nk-tb-col nk-tb-col-tools text-end">
                                            <span class="sub-text">Action</span>
                                        </div>
                                    </div>

                                    <!-- Table Rows -->
                                    <div class="nk-tb-item">
                                        <div class="nk-tb-col nk-tb-col-check">
                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                <input type="checkbox" class="custom-control-input" id="id-01">
                                                <label class="custom-control-label" for="id-01"></label>
                                            </div>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span class="tb-lead">0000163</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span>01/10/2024</span>
                                        </div>
                                        <div class="nk-tb-col text-end">
                                            <span class="tb-amount">645.00</span>
                                        </div>
                                        <div class="nk-tb-col text-end">
                                            <span class="tb-amount">0.00</span>
                                        </div>
                                        <div class="nk-tb-col text-end">
                                            <span class="tb-amount">645.00</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span>05/09/25</span>
                                        </div>
                                        <div class="nk-tb-col">
                                            <span class="badge badge-dim bg-warning">Pending</span>
                                        </div>
                                        <div class="nk-tb-col nk-tb-col-tools">
                                            <ul class="nk-tb-actions gx-1">
                                                <li>
                                                    <div class="drodown">
                                                        <a href="#"
                                                            class="dropdown-toggle btn btn-icon btn-trigger"
                                                            data-bs-toggle="dropdown">
                                                            <em class="icon ni ni-more-h"></em>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <ul class="link-list-opt no-bdr">
                                                                <li><a href="#"><em
                                                                            class="icon ni ni-edit"></em><span>Edit</span></a>
                                                                </li>
                                                                <li><a href="#"><em
                                                                            class="icon ni ni-eye"></em><span>View</span></a>
                                                                </li>
                                                                <li><a href="#"><em
                                                                            class="icon ni ni-trash"></em><span>Delete</span></a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <!-- Repeat similar structure for other rows -->
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Totals -->
                    <div class="card">
                        <div class="card-inner">
                            <div class="row justify-content-end">
                                <div class="col-md-4 text-end">
                                    <p class="mb-1">TOTAL SELECTED: <span class="fw-bold">$1,645.00</span></p>
                                    <p class="mb-0">TOTAL AMOUNT BALANCE: <span class="fw-bold">$1,645.00</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="mt-4">
                        <button class="btn btn-outline-light" data-bs-toggle="modal" data-bs-target="#newCheckModal">
                            <em class="icon ni ni-plus"></em><span>New Check</span>
                        </button>
                        <button class="btn btn-outline-light" data-bs-toggle="modal"
                            data-bs-target="#voidCheckModal">
                            <em class="icon ni ni-cross"></em><span>Void Check</span>
                        </button>
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#acceptPaymentModal">
                            <em class="icon ni ni-check"></em><span>Accept Payment</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Payment Modal -->
    <div class="modal fade" id="acceptPaymentModal">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Accept Payment</h5>
                    <a href="#" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form action="#" class="form-validate">
                        <div class="form-group">
                            <label class="form-label">Payment Amount</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" placeholder="0.00">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Payment Method</label>
                            <div class="form-control-wrap">
                                <select class="form-select">
                                    <option value="cash">Cash</option>
                                    <option value="check">Check</option>
                                    <option value="card">Credit Card</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Payment Date</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control date-picker">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label">Notes</label>
                            <div class="form-control-wrap">
                                <textarea class="form-control" rows="3"></textarea>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary">Accept Payment</button>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>
