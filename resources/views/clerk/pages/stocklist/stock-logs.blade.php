<x-app-layout>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">

                    <!-- Page Header -->
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Stock Logs</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Manage and view stock logs with filters and export options.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- End Page Header -->

                    <!-- Filter Section -->
                    <div class="nk-block d-flex justify-content-center">
                        <div class="card card-bordered" style="margin-right: auto; max-width: 600px; width: 100%;">
                            <div class="card-inner">
                                <form id="dateFilterForm" class="row gy-3">
                                    <div class="col-12">
                                        <label for="startDate" class="form-label">Date</label>
                                        <input type="date" id="startDate" name="start_date" class="form-control">
                                    </div>
                                    <div class="col-12 d-flex justify-content-between">
                                        <button type="button" id="filterButton" class="btn btn-primary">Filter</button>
                                        <button type="reset" class="btn btn-secondary">Reset</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- End Filter Section -->

                    <!-- Mass Action Buttons -->
                    <div class="nk-block d-flex justify-content-end">
                        <button type="button" id="massSellButton" class="btn btn-success me-2" disabled>
                            Mass Sell
                        </button>
                        <button type="button" id="massDisposalButton" class="btn btn-danger" disabled>
                            Mass Disposal
                        </button>
                    </div>

                    <!-- Stock Logs Table -->
                    <div class="nk-block nk-block-lg mt-4">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <table class="datatable-init nowrap table" id="stockLogsTable" data-export-title="Export">
                                    <thead>
                                        <tr>
                                            <th>
                                                <input type="checkbox" id="selectAll" />
                                            </th>
                                            <th>Product (SKU)</th>
                                            <th>Kilos</th>
                                            <th>Price</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Sample row -->
                                        <tr>
                                            <td>
                                                <input type="checkbox" class="row-select" data-sku="P-1" />
                                            </td>
                                            <td>P-1</td>
                                            <td>39</td>
                                            <td>₱200</td>
                                            <td>2024-12-01</td>
                                            <td>11pm</td>
                                            <td>
                                                <button type="button" class="btn btn-success btn-sm" onclick="handleSell('P-1')">
                                                    Sell
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" onclick="handleDisposal('P-1')">
                                                    Disposal
                                                </button>
                                            </td>
                                        </tr>
                                        <!-- Additional rows dynamically populated -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <!-- End Stock Logs Table -->

                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAllCheckbox = document.getElementById('selectAll');
            const rowCheckboxes = document.querySelectorAll('.row-select');
            const massSellButton = document.getElementById('massSellButton');
            const massDisposalButton = document.getElementById('massDisposalButton');

            // Enable or disable mass action buttons based on selection
            function toggleMassActionButtons() {
                const anyChecked = Array.from(rowCheckboxes).some(checkbox => checkbox.checked);
                massSellButton.disabled = !anyChecked;
                massDisposalButton.disabled = !anyChecked;
            }

            // Handle "Select All" functionality
            selectAllCheckbox.addEventListener('change', function () {
                rowCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllCheckbox.checked;
                });
                toggleMassActionButtons();
            });

            // Handle individual row checkbox selection
            rowCheckboxes.forEach(checkbox => {
                checkbox.addEventListener('change', function () {
                    const allChecked = Array.from(rowCheckboxes).every(checkbox => checkbox.checked);
                    selectAllCheckbox.checked = allChecked;
                    toggleMassActionButtons();
                });
            });

            // Handle Mass Sell
            massSellButton.addEventListener('click', function () {
                const selectedSKUs = Array.from(rowCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.dataset.sku);
                alert('Selling products with SKUs: ' + selectedSKUs.join(', '));
                // Add your mass sell logic here
            });

            // Handle Mass Disposal
            massDisposalButton.addEventListener('click', function () {
                const selectedSKUs = Array.from(rowCheckboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.dataset.sku);
                alert('Disposing products with SKUs: ' + selectedSKUs.join(', '));
                // Add your mass disposal logic here
            });
        });
    </script>
</x-app-layout>


