<div class="modal fade" id="productsModal" tabindex="-1" aria-labelledby="productsModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="productsModal">Create Products</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form>
                    <!-- Product sku -->
                    <div class="form-group mb-3">
                        <label class="form-label" for="product_sku">Item Code (SKU)</label>
                        <input type="text" name="product_sku" class="form-control" id="product_sku" required >
                    </div>

                    <!-- Stock Category -->
                    <div class="form-group mb-3">
                        <label class="form-label" for="product_name">Product Name</label>
                        <input type="text" name="product_name" class="form-control" id="product_name" required>
                    </div>

                    <!-- Stock Quantity -->
                    <div class="form-group mb-3">
                        <label class="form-label" for="product_quantity">Product Quantity</label>
                        <input type="text" name="product_quantity" class="form-control" id="product_quantity" required>
                    </div>
                    <div class="form-group mb-3">
                        <label class="form-label" for="product_category">Category</label>
                        <select name="product_category" class="form-control" id="product_category" required>
                            <option value="">-----------------------Select a Category----------------------</option>
                            <option value="electronics">Electronics</option>
                            <option value="clothing">Clothing</option>
                            <option value="furniture">Furniture</option>
                            <!-- Add more options as needed -->
                        </select>
                    </div>


                    <div class="form-group mb-3">
                        <label class="form-label" for="product_price">Price</label>
                        <input type="number" name="product_price" class="form-control" id="product_price" required>
                    </div>


                    <!-- Save Button -->
                    <div class="form-group">
                        <button type="submit" class="btn btn-lg btn-primary">Save Stock</button>
                    </div>
                </form>
            </div>

            <!-- Modal Footer -->
            <div class="modal-footer bg-light">
                <span class="sub-text">Stock Entry Footer</span>
            </div>
        </div>
    </div>
</div>
