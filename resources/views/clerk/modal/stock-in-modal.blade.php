<div class="modal fade" id="stockinModal" tabindex="-1" aria-labelledby="stockinModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="stockinModalLabel">Create Stock Entry</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Modal Body -->
            <div class="modal-body">
                <form>
                    <!-- Stock Name -->
                    <div class="form-group">
                        <label class="form-label">Select Item Code (Sku)</label>
                        <div class="form-control-wrap">
                            <select class="form-select js-select2">
                                <option value="select">Select</option>
                                <option value="2">Option 2</option>
                                <option value="3">Option 3</option>
                            </select>
                        </div>
                    </div>

                    <!-- Stock Quantity -->
                    <div class="form-group mb-3">
                        <label class="form-label" for="stock-quantity">Stock Quantity</label>
                        <input type="number" name="stock_quantity" class="form-control" id="stock-quantity" required>
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
