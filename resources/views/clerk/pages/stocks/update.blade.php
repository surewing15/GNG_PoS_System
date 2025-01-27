<x-app-layout>
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head d-flex justify-content-between align-items-center">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">Stocks</h4>
            </div>

            <div class="nk-block-tools-opt">
                <a href="#" data-bs-toggle="modal" data-bs-target="#stockinModal" class="toggle btn btn-icon btn-primary d-md-none">
                    <em class="icon ni ni-plus"></em>
                </a>
                <a href="#" data-bs-toggle="modal" data-bs-target="#stockinModal" class="toggle btn btn-primary d-none d-md-inline-flex">
                    <em class="icon ni ni-plus"></em><span>Stock In</span>
                </a>
            </div>
        </div>

        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <form>
                    <table class="datatable-init-export nowrap table" data-export-title="Export">
                        <thead>
                            <tr>
                                <th>Item Code (SKU)</th>
                                <th>Stock Name</th>
                                <th>Stock</th>
                                <th>Products</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Simulated data -->
                            <tr>
                                <td>
                                    <input type="text" class="form-control" value="SKU1234" disabled />
                                </td>
                                <td>
                                    <input type="text" class="form-control" value="Sample Stock 1" disabled />
                                </td>
                                <td>
                                    <input type="number" class="form-control" value="10" />
                                </td>
                                <td>
                                    <input type="text" class="form-control" value="Sample Product A" />
                                </td>
                                <td>
                                    <input type="text" class="form-control" value="13123" />
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger">Cancel</button>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <input type="text" class="form-control" value="SKU5678" disabled />
                                </td>
                                <td>
                                    <input type="text" class="form-control" value="Sample Stock 2" disabled />
                                </td>
                                <td>
                                    <input type="number" class="form-control" value="20" />
                                </td>
                                <td>
                                    <input type="text" class="form-control" value="Sample Product B" />
                                </td>
                                <td>
                                    <input type="text" class="form-control" value="20000" />
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-danger">Cancel</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>

                    <!-- Single Save Button for the entire form -->
                    <div class="form-group mt-3">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div><!-- .card-preview -->
    </div> <!-- nk-block -->

</x-app-layout>


