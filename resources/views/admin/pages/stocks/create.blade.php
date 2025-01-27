<x-app-layout>
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head d-flex justify-content-between align-items-center">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">Add Adjustments</h4>
            </div>


        <div class="nk-block-tools-opt">
            <a href="/stocks" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-arrow-left"></em><span>Back</span></a>
        </div>

        </div>



        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <!-- Row to align form elements -->
                <div class="row g-3">
                    <!-- Column for Reference Name -->
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="default-1-03" class="form-label">Reference</label>
                            <input type="text" class="form-control" id="default-1-03" disabled value="STK">
                        </div>
                    </div>


                    <div class="col-md-3">
                        <div class="mb-3">
                            <em class="icon ni ni-calendar-alt"></em>
                            <label for="outlined-date-picker" class="form-label">Date</label>
                            <input type="text" class="form-control form-control-xl form-control-outlined date-picker" id="outlined-date-picker">
                        </div>
                    </div>
                </div>

                <!-- Row for Table -->
                <div class="row">
                    <div class="col-12">
                        <table class="datatable-init-export nowrap table" data-export-title="Export">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product Name</th>
                                    <th>SKU</th>
                                    <th>Stock</th>
                                    <th>Quantity</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>October 21, 2024</td>
                                    <td>0012</td>
                                    <td>Product Name</td>
                                    <td><div class="form-control-wrap number-spinner-wrap">
                                        <button class="btn btn-icon btn-primary number-spinner-btn number-minus" data-number="minus"><em class="icon ni ni-minus"></em></button>
                                        <input type="number" class="form-control number-spinner" value="0">
                                        <button class="btn btn-icon btn-primary number-spinner-btn number-plus" data-number="plus"><em class="icon ni ni-plus"></em></button>
                                    </div></td>
                                    <td>Product Name</td>
                                    <td>Product Name</td>
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
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div><!-- .card-preview -->
    </div> <!-- nk-block -->
</x-app-layout>




