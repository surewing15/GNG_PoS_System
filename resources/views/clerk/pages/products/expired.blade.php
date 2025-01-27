<x-app-layout>
    <div class="nk-block nk-block-lg">


        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Stocks Report</h3>
                </div><!-- .nk-block-head-content -->
                <div class="nk-block-head-content">
                    <div class="toggle-wrap nk-block-tools-toggle">
                        <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                        <div class="toggle-expand-content" data-content="more-options">
                            <ul class="nk-block-tools g-3">
                                <li>


                                            <div class="form-control-wrap">
                                                <div class="form-icon form-icon-right">
                                                    <em class="icon ni ni-calendar-alt"></em>
                                                </div>
                                                <input type="text" class="form-control form-control-xl form-control-outlined date-picker" id="outlined-date-picker">
                                                <label class="form-label-outlined" for="outlined-date-picker">Filter</label>
                                            </div>


                                </li>


                            </ul>
                        </div>
                    </div>
                </div><!-- .nk-block-head-content -->
            </div><!-- .nk-block-between -->
        </div>

        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <table class="datatable-init-export nowrap table mt-4" data-export-title="Export">
                    <thead>
                        <tr>
                            <th>Product Code (SKU)</th>
                            <th>Kilos</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>100</td>
                            <td>10</td>
                            <td>October 21, 2024</td>
                            <td>
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                        data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="#"><em class="icon ni ni-eye"></em><span>View</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div><!-- .card-preview -->
    </div> <!-- nk-block -->
</x-app-layout>


