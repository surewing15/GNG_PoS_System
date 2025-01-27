<div class="nk-add-product toggle-slide toggle-slide-right" data-content="addProduct" data-toggle-screen="any"
    data-toggle-overlay="true" data-toggle-body="true" data-simplebar>
    <div class="nk-block-head">
        <div class="nk-block-head-content">
            <h5 class="nk-block-title">New Product</h5>
            <div class="nk-block-des">
                <p>Add information to create a new product.</p>
            </div>
        </div>
    </div>
    <!-- .nk-block-head -->
    <div class="nk-block">
        <form action="{{ route('product.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="row g-3">

                <!-- Category Dropdown -->
                <!-- Category Dropdown -->
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="p_category">Category</label>
                        <div class="form-control-wrap">
                            <select name="category" class="form-control" id="p_category" required>
                                @foreach ($categoryOptions as $category)
                                    <option value="{{ $category }}">{{ ucfirst(str_replace('_', ' ', $category)) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <!-- SKU Field -->
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="p_sku">SKU</label>
                        <div class="form-control-wrap">
                            <input type="text" name="p_sku" class="form-control" id="p_sku" required>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="p_description">Description</label>
                        <div class="form-control-wrap">
                            <select name="p_description" class="form-control" id="p_description" required>
                                <option value="">Select Description</option>
                                <option value="900GRMS.">900GRMS.</option>
                                <option value="1KG.">1KG.</option>
                                <option value="1.1KGS.">1.1KGS.</option>
                                <option value="1.2KGS">1.2KGS</option>
                                <option value="1.3-1.4KGS.">1.3-1.4KGS.</option>
                                <option value="800GRMS.">800GRMS.</option>
                                <option value="700GRMS.">700GRMS.</option>
                                <option value="ASSRTD">ASSRTD</option>
                                <option value="600GRMS">600GRMS</option>
                                <option value="PCK.">PCK.</option>
                                <option value="KG.">KG.</option>

                            </select>
                        </div>
                    </div>
                </div>


                <div class="col-12">
                    <div class="form-group">
                        <label class="form-label" for="p_price">Price</label>
                        <div class="form-control-wrap">
                            <input type="text" name="p_price" class="form-control" id="p_price" required>
                        </div>
                    </div>
                </div>
                @error('p_price')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

                @error('p_sku')
                    <span class="text-danger">{{ $message }}</span>
                @enderror


                @error('p_category')
                    <span class="text-danger">{{ $message }}</span>
                @enderror

                @error('p_description')
                    <span class="text-danger">{{ $message }}</span>
                @enderror


                <!-- Product Image Upload -->
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
                    @error('p_image')
                        <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Submit Button -->
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">
                        <em class="icon ni ni-plus"></em><span>Add New</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
