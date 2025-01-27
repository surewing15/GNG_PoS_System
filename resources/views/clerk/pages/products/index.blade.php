<x-app-layout>
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head d-flex justify-content-between align-items-center">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">Products</h4>
            </div>

        </div>

        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <table class="datatable-init-export nowrap table" data-export-title="Export">

                    <thead>
                        <tr>
                            <th>Product Image</th>
                            <th>Product Code (SKU)</th>
                            <th>Product Name</th>
                            <th>Weight</th>
                            <th>Date</th>
                            <th>Action</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#</td>
                            <td>P1</td>
                            <td>Chicken Breast</td>
                            <td>100</td>
                            <td>Ocotber 21, 2024</td>
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
