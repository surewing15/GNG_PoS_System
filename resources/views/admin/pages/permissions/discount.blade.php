<x-app-layout>
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head d-flex justify-content-between align-items-center">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">Discount Code Management</h4>
            </div>
            <div class="nk-block-head-content">
                <a href="#" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addDiscountModal">Add New Discount Code</a>
            </div>
        </div>

        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <div class="row">
                    <div class="col-12">
                        <table class="datatable-init-export nowrap table" data-export-title="Discount Codes Export">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Discount Code</th>
                                    <th>Expiry Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>admin123</td>
                                    <td>2024-12-31</td>
                                    <td>
                                        <div class="dropdown">
                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown">
                                                <em class="icon ni ni-more-h"></em>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <ul class="link-list-opt no-bdr">
                                                    <li><a href="#"><em class="icon ni ni-eye"></em><span>View</span></a></li>
                                                    <li><a href="#"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                                                    <li><a href="#"><em class="icon ni ni-trash"></em><span>Delete</span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                                <!-- Add more discount codes here -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- .card-preview -->
    </div> <!-- nk-block -->

    <!-- Modal to Add/Edit Discount Code -->
    <div class="modal fade" id="addDiscountModal" tabindex="-1" aria-labelledby="addDiscountModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDiscountModalLabel">Add New Discount Code</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="#" method="POST">
                        <!-- Discount Code -->
                        <div class="mb-3">
                            <label for="discount-code" class="form-label">Discount Code</label>
                            <input type="text" class="form-control" id="discount-code" placeholder="Enter Discount Code" required>
                        </div>

                        {{-- <!-- Discount Type -->
                        <div class="mb-3">
                            <label for="discount-type" class="form-label">Discount Type</label>
                            <select class="form-select" id="discount-type" required>
                                <option value="percentage">Percentage</option>
                                <option value="amount">Fixed Amount</option>
                            </select>
                        </div> --}}

                        {{-- <!-- Discount Value -->
                        <div class="mb-3">
                            <label for="discount-value" class="form-label">Discount Value</label>
                            <input type="number" class="form-control" id="discount-value" placeholder="Enter Discount Value" required>
                        </div> --}}

                        <!-- Expiry Date -->
                        <div class="mb-3">
                            <label for="expiry-date" class="form-label">Expiry Date</label>
                            <input type="date" class="form-control" id="expiry-date" required>
                        </div>

                        <!-- Submit Button -->
                        <div class="mb-3 text-end">
                            <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>




