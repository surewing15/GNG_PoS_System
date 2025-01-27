<x-app-layout>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">


                    <!-- Truck List Section -->
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Trucking Report</h3>
                            </div>
                        </div>
                    </div>

                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <table class="datatable-init nowrap table" data-export-title="Export">
                                    <thead>
                                        <tr>
                                            <th>Delivery ID #</th>
                                            <th>Truck Name</th>
                                            <th>Customer Name</th>

                                            <th>Status</th>
                                            <th>Date</th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>02103</td>
                                            <td>Ford</td>
                                            <td>Jhongpaks</td>
                                            <td>
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            </td>
                                            <td>october 1, 2024</td>


                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Truck Modal -->
    @include('cashier.modal.trucking-receipt')

</x-app-layout>
