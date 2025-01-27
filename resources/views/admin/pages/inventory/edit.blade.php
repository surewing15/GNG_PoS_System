<x-app-layout>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Edit Inventory Item</h3>
                            </div>
                        </div>
                    </div>

                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <form>
                                    <!-- Item Code (SKU) -->
                                    <div class="form-group">
                                        <label class="form-label" for="sku">Item Code (SKU)</label>
                                        <input type="text" class="form-control" id="sku" name="sku" placeholder="Enter item SKU">
                                    </div>

                                    <!-- Over-All Kilos -->
                                    <div class="form-group">
                                        <label class="form-label" for="kilos">Over-All Kilos</label>
                                        <input type="number" class="form-control" id="kilos" name="kilos" placeholder="Enter total kilos" step="0.01">
                                    </div>

                                    <!-- Price -->
                                    <div class="form-group">
                                        <label class="form-label" for="price">Price</label>
                                        <input type="number" class="form-control" id="price" name="price" placeholder="Enter price" step="0.01">
                                    </div>

                                    <!-- Buttons -->
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Update Item</button>
                                        <a href="#" class="btn btn-secondary">Cancel</a>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div> <!-- nk-block -->
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


