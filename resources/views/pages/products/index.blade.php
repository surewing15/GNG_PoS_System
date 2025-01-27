<x-app-layout>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <!-- Page Header -->
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Customers</h3>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="more-options">
                                        <em class="icon ni ni-more-v"></em>
                                    </a>
                                    <div class="toggle-expand-content" data-content="more-options">
                                        <ul class="nk-block-tools g-3">
                                            <!-- Search Input -->
                                            <li>
                                                <div class="form-control-wrap">
                                                    <div class="form-icon form-icon-right">
                                                        <em class="icon ni ni-search"></em>
                                                    </div>
                                                    <input type="text" class="form-control" id="default-04" placeholder="Search by name">
                                                </div>
                                            </li>
                                            <!-- Filter Dropdown -->
                                            <li>
                                                <div class="drodown">
                                                    <a href="#" class="dropdown-toggle dropdown-indicator btn btn-outline-light btn-white" data-bs-toggle="dropdown">Filter</a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        <ul class="link-list-opt no-bdr">
                                                            <li><a href="#"><span>Actived</span></a></li>
                                                            <li><a href="#"><span>Inactived</span></a></li>
                                                            <li><a href="#"><span>Blocked</span></a></li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                            <!-- Add Button and Modal Trigger -->
                                            <li class="nk-block-tools-opt">
                                                <a href="#" class="btn btn-icon btn-primary d-md-none" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    <em class="icon ni ni-plus"></em>
                                                </a>
                                                <a href="#" class="btn btn-primary d-none d-md-inline-flex" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                    <em class="icon ni ni-plus"></em><span>Add</span>
                                                </a>

                                                <!-- Modal -->
                                                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <!-- Modal Header -->
                                                            <div class="modal-header">
                                                                <h5 class="modal-title fw-bold" id="exampleModalLabel">Customer Info</h5>
                                                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                            </div>
                                                            <!-- Modal Body -->
                                                            <div class="modal-body">
                                                                <form action="" method="" class="form-validate is-alter" novalidate>

                                                                    <!-- Full Name Field -->
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="full-name">Customer Name</label>
                                                                        <div class="form-control-wrap">
                                                                            <input type="text" name="CustomerName" class="form-control" id="full-name" required>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Customer Address Field -->
                                                                    <div class="form-group">
                                                                        <label class="form-label">Customer Address</label>
                                                                        <div class="form-control-wrap">
                                                                            <input type="text" name="CustomerAddress" class="form-control" required>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Phone Number Field -->
                                                                    <div class="form-group">
                                                                        <label class="form-label" for="phone-no">Phone Number</label>
                                                                        <div class="form-control-wrap">
                                                                            <input type="text" name="PhoneNumber" class="form-control" id="phone-no" required>
                                                                        </div>
                                                                    </div>
                                                                    <!-- Save Button -->
                                                                    <div class="form-group">
                                                                        <button type="submit" class="btn btn-lg btn-primary">Save Information</button>&ensp;
                                                                    </div>
                                                                </form>
                                                            </div>
                                                            <!-- Modal Footer -->
                                                            <div class="modal-footer bg-light">
                                                                <span class="sub-text">Modal Footer Text</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->

                    <!-- Data Table Block -->
                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <table class="datatable-init nowrap nk-tb-list is-separate" data-auto-responsive="false">
                                    <thead>
                                        <tr class="nk-tb-item nk-tb-head">
                                            <th class="nk-tb-col nk-tb-col-check">
                                                <div class="custom-control custom-control-sm custom-checkbox notext">
                                                    <input type="checkbox" class="custom-control-input" id="puid">
                                                    <label class="custom-control-label" for="puid"></label>
                                                </div>
                                            </th>
                                            <th class="nk-tb-col tb-col-sm"><span>Product ID</span></th>
                                            <th class="nk-tb-col"><span>SKU</span></th>
                                            <th class="nk-tb-col"><span>Product Name</span></th>
                                            <th class="nk-tb-col"><span>Cost Price</span></th>
                                            <th class="nk-tb-col tb-col-md"><span>Selling Price</span></th>
                                            <th class="nk-tb-col tb-col-md"><span>Product Category</span></th>
                                            <th class="nk-tb-col tb-col-md"><span>Unit Of Measure</span></th>
                                            <th class="nk-tb-col tb-col-md"><span>Conversion Factor</span></th>
                                            <th class="nk-tb-col tb-col-md"><span>Last Updated</span></th>
                                            <th class="nk-tb-col tb-col-md"><em class="tb-asterisk icon ni ni-star-round"></em></th>
                                            <th class="nk-tb-col nk-tb-col-tools"></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr class="nk-tb-item">
                                            <td class="nk-tb-col nk-tb-col-check">
                                                <div class="custom-control custom-control-sm custom-checkbox notext">
                                                    <input type="checkbox" class="custom-control-input" id="puid1">
                                                    <label class="custom-control-label" for="puid1"></label>
                                                </div>
                                            </td>
                                            <td class="nk-tb-col">
                                                <span class="tb-sub">UY3749</span>
                                            </td>
                                            <td class="nk-tb-col">
                                                <span class="tb-sub">SKU12345</span>
                                            </td>
                                            <td class="nk-tb-col tb-col-sm">
                                                <span class="tb-product">
                                                    <img src="./images/product/a.png" alt="" class="thumb">
                                                    <span class="title">head</span>
                                                </span>
                                            </td>
                                            <td class="nk-tb-col">
                                                <span class="tb-sub">$49.99</span>
                                            </td>
                                            <td class="nk-tb-col tb-col-md">
                                                <span class="tb-sub">$59.99</span>
                                            </td>
                                            <td class="nk-tb-col tb-col-md">
                                                <span class="tb-sub">Fitness</span>
                                            </td>
                                            <td class="nk-tb-col tb-col-md">
                                                <span class="tb-sub">Piece</span>
                                            </td>
                                            <td class="nk-tb-col tb-col-md">
                                                <span class="tb-sub">1</span>
                                            </td>
                                            <td class="nk-tb-col tb-col-md">
                                                <span class="tb-sub">2024-10-04</span>
                                            </td>
                                            <td class="nk-tb-col tb-col-md">
                                                <div class="asterisk tb-asterisk">
                                                    <a href="#"><em class="asterisk-off icon ni ni-star"></em><em class="asterisk-on icon ni ni-star-fill"></em></a>
                                                </div>
                                            </td>
                                            <td class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1 my-n1">
                                                    <li class="me-n1">
                                                        <div class="dropdown">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-end">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <li><a href="#"><em class="icon ni ni-edit"></em><span>Edit Product</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-eye"></em><span>View Product</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-activity-round"></em><span>Product Orders</span></a></li>
                                                                    <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove Product</span></a></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .card-preview -->
                    </div> <!-- nk-block -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
