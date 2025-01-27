<x-app-layout>
    <div class="nk-add-product toggle-slide toggle-slide-right" data-content="editProduct" data-toggle-screen="any" data-toggle-overlay="true" data-toggle-body="true" data-simplebar>
        <div class="nk-block-head">
            <div class="nk-block-head-content">
                <h5 class="nk-block-title">Edit Product</h5>
                <div class="nk-block-des">
                    <p>Update the product information below.</p>
                </div>
            </div>
        </div>
        <!-- .nk-block-head -->
        <div class="nk-block">
            <form action="" method="POST">
                <input type="hidden" name="product_id" value="{{ product.id }}">

                <div class="row g-3">
                    <!-- Product Name -->
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="p_name">Product Name</label>
                            <div class="form-control-wrap">
                                <input type="text" name="p_name" class="form-control" id="p_name" value="{{ product.name }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- SKU -->
                    <div class="col-mb-6">
                        <div class="form-group">
                            <label class="form-label" for="p_sku">SKU</label>
                            <div class="form-control-wrap">
                                <input type="text" name="p_sku" class="form-control" id="p_sku" value="{{ product.sku }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Category -->
                    <div class="col-md-12">
                        <div class="form-control-wrap">
                            <label class="form-label" for="p_category">Category</label>
                            <div class="input-group">
                                <select class="form-select" name="p_category" id="p_category" required>
                                    <option disabled>Select Category</option>
                                    <option value="lobot" {{ product.category == 'lobot' ? 'selected' : '' }}>Lobot</option>
                                    <option value="sampot" {{ product.category == 'sampot' ? 'selected' : '' }}>Sampot</option>
                                </select>
                                <div class="input-group-append">
                                    <button type="button" data-bs-toggle="modal" data-bs-target="#categoryModal" class="btn btn-outline-primary btn-dim">Add</button>
                                    <button type="button" onclick="deleteCategory()" class="btn btn-outline-danger btn-dim">Delete</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Cost -->
                    <div class="col-mb-6">
                        <div class="form-group">
                            <label class="form-label" for="p_cost">Cost</label>
                            <div class="form-control-wrap">
                                <input type="number" step="0.01" name="p_cost" class="form-control" id="p_cost" value="{{ product.cost }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="col-mb-6">
                        <div class="form-group">
                            <label class="form-label" for="p_price">Price</label>
                            <div class="form-control-wrap">
                                <input type="number" step="0.01" name="p_price" class="form-control" id="p_price" value="{{ product.price }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Quantity -->
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="p_quantity">Quantity</label>
                            <div class="form-control-wrap">
                                <input type="number" name="p_quantity" class="form-control" id="p_quantity" value="{{ product.quantity }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Unit -->
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="p_unit">Unit</label>
                            <select class="form-select" name="p_unit" id="p_unit" required>
                                <option disabled>Select Unit</option>
                                <option value="lobot" {{ product.unit == 'lobot' ? 'selected' : '' }}>Lobot</option>
                                <option value="sampot" {{ product.unit == 'sampot' ? 'selected' : '' }}>Sampot</option>
                            </select>
                        </div>
                    </div>

                    <!-- Stock Alert -->
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="p_stock_alert">Stock Alert</label>
                            <div class="form-control-wrap">
                                <input type="number" name="p_stock_alert" class="form-control" id="p_stock_alert" value="{{ product.stock_alert }}" required>
                            </div>
                        </div>
                    </div>

                    <!-- Note -->
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="p_note">Note</label>
                            <div class="form-control-wrap">
                                <textarea name="p_note" class="form-control" id="p_note">{{ product.note }}</textarea>
                            </div>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <div class="col-12">
                        <div class="form-group">
                            <label class="form-label" for="p_image">Product Image</label>
                            <div class="upload-zone small bg-lighter my-2">
                                <input type="file" name="p_image" class="form-control" id="p_image" accept="image/*">
                                <div class="dz-message">
                                    <span class="dz-message-text">Drag and drop a file or click to upload.</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="col-12">
                        <button type="submit" class="btn btn-primary"><em class="icon ni ni-save"></em><span>Save Changes</span></button>
                    </div>
                </div>
            </form>
        </div>
        <!-- .nk-block -->
    </div>

</x-app-layout>
