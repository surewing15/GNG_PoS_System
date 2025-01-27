<x-app-layout>
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head d-flex justify-content-between align-items-center">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">Edit Products</h4>
            </div>

            <div class="nk-block-tools-opt">
                <a href="{{ url()->previous() }}" class="btn btn-secondary">
                    <em class="icon ni ni-arrow-left"></em><span>Back</span>
                </a>

            </div>
        </div>

        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <form action="" method=""> <!-- Add form action for updating the products -->


                    <table class="datatable-init-export nowrap table" data-export-title="Export">
                        <thead>
                            <tr>
                                <th>Item Code (SKU)</th>
                                <th>Product Name</th>
                                <th>Product Quantity</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><input type="text" class="form-control" name="sku[]" value="P1" readonly></td>
                                <td><input type="text" class="form-control" name="product_name[]" value="Chicken Breast"></td>
                                <td><input type="number" class="form-control" name="product_quantity[]" value="100"></td>
                                <td>
                                    <select name="category[]" class="form-control">
                                        <option value="chicken wings" selected>Chicken Wings</option>
                                        <option value="beef">Beef</option>
                                        <option value="pork">Pork</option>
                                        <!-- Add more options as necessary -->
                                    </select>
                                </td>
                                <td><input type="number" class="form-control" name="price[]" value="200.00" step="0.01"></td>
                                <td>
                                    <button type="submit" class="btn btn-primary">Save</button>
                                    <a href="#" class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                            <!-- Repeat for more rows -->
                        </tbody>
                    </table>
                </form>
            </div>
        </div><!-- .card-preview -->
    </div> <!-- nk-block -->
    @include('clerk.modal.products-modal')
</x-app-layout>




