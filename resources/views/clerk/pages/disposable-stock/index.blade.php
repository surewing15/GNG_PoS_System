<x-app-layout>
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Disposable Stock</h3>
                </div><!-- .nk-block-head-content -->
                <div class="nk-block-head-content">
                    <div class="toggle-wrap nk-block-tools-toggle">
                        <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="more-options">
                            <em class="icon ni ni-more-v"></em>
                        </a>
                        <div class="toggle-expand-content" data-content="more-options">
                            <ul class="nk-block-tools g-3">
                                <li>
                                    <div class="form-control-wrap">
                                        <div class="form-icon form-icon-right">
                                            <em class="icon ni ni-calendar-alt"></em>
                                        </div>
                                        <input type="text" class="form-control form-control-xl form-control-outlined date-picker"
                                               id="outlined-date-picker">
                                        <label class="form-label-outlined" for="outlined-date-picker">Filter by Date</label>
                                    </div>
                                </li>
                                <li>
                                    <div class="form-control-wrap">
                                        <input type="text" class="form-control" placeholder="Search by SKU or Product">
                                    </div>
                                </li>
                                <li>
                                    <button class="btn btn-primary"><em class="icon ni ni-reports"></em> Generate Report</button>
                                </li>
                                <!-- Mass Save Button -->
                                <li>
                                    <button class="btn btn-success" id="mass-save-btn"><em class="icon ni ni-save"></em> Mass Save</button>
                                </li>
                                <!-- Mass Dispose Button -->
                                <li>
                                    <button class="btn btn-danger" id="mass-dispose-btn"><em class="icon ni ni-trash"></em> Mass Dispose</button>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div><!-- .nk-block-head-content -->
            </div><!-- .nk-block-between -->
        </div>

        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <table class="datatable-init-export nowrap table mt-4" data-export-title="Disposable Stock">
                    <thead>
                        <tr>
                            <th>
                                <input type="checkbox" id="select-all" />
                            </th>
                            <th>Product Code (SKU)</th>
                            <th>Quantity (Kilos)</th>
                            <th>Last Updated</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="checkbox" class="row-checkbox" />
                            </td>
                            <td>P-1</td>
                            <td>100</td>
                            <td>November 26, 2024</td>
                            <td>
                                <div class="dropdown">
                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown">
                                        <em class="icon ni ni-more-h"></em>
                                    </a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="#"><em class="icon ni ni-eye"></em><span>View</span></a></li>
                                            <li><a href="#"><em class="icon ni ni-trash"></em><span>Dispose</span></a></li>
                                            <li><a href="#"><em class="icon ni ni-edit"></em><span>Edit</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <!-- Additional rows -->
                    </tbody>
                </table>
            </div>
        </div><!-- .card-preview -->
    </div> <!-- nk-block -->

    <script>
        // Select all functionality
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.row-checkbox');
            checkboxes.forEach(checkbox => checkbox.checked = this.checked);
        });

        // Mass Dispose functionality
        document.getElementById('mass-dispose-btn').addEventListener('click', function() {
            const selectedItems = [];
            document.querySelectorAll('.row-checkbox:checked').forEach(checkbox => {
                const row = checkbox.closest('tr');
                const sku = row.querySelector('td:nth-child(2)').textContent.trim();
                selectedItems.push(sku);
            });

            if (selectedItems.length > 0) {
                if (confirm(`Are you sure you want to dispose of the selected items: ${selectedItems.join(', ')}?`)) {
                    // Send the selectedItems to the server for processing
                    console.log('Items to dispose:', selectedItems);
                    alert('Selected items have been sent for disposal.');
                }
            } else {
                alert('Please select at least one item to dispose.');
            }
        });
    </script>
</x-app-layout>

