<x-app-layout>
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head d-flex justify-content-between align-items-center">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">Stocks</h4>
            </div>

            <div class="nk-block-tools-opt">
                <a href="/stocks/adjust" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Adjustments</span></a>
            </div>


        </div>

        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <table class="datatable-init-export nowrap table" data-export-title="Export">

                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Ref ID</th>
                            <th>Products</th>
                            <th>Action</th>


                        </tr>
                    </thead>
                    <tbody>
                        <tr>


                            <td>october 21</td>
                            <td>0012</td>
                            <td>product</td>
                            <td> <div class="dropdown">
                                <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <ul class="link-list-opt no-bdr">
                                        <li><a href="#"><em class="icon ni ni-trash"></em><span>View</span></a></li>
                                        <li><a href="#"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                                        <li><a href="#"><em class="icon ni ni-trash"></em><span>Delete</span></a></li>

                                    </ul>
                                </div>
                            </div></td>


                        </tr>

                    </tbody>
                </table>
            </div>
        </div><!-- .card-preview -->
    </div> <!-- nk-block -->
</x-app-layout>




