<x-app-layout>
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head d-flex justify-content-between align-items-center">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">Products</h4>
            </div>

            <div class="nk-block-tools-opt">
                <li class="nk-block-tools-opt">
                    <a href="#" data-target="addProduct" class="toggle btn btn-icon btn-primary d-md-none"><em
                            class="icon ni ni-plus"></em></a>
                    <a href="#" data-target="addProduct" class="toggle btn btn-primary d-none d-md-inline-flex"><em
                            class="icon ni ni-plus"></em><span>Add Product</span></a>
                </li>
            </div>

        </div>

        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <table class="datatable-init-export nowrap table" data-export-title="Export">

                    <thead>
                        <tr>
                            <th>Product Image</th>
                            <th>Product Code (SKU)</th>
                            <th>Weight</th>
                            <th>Price</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                            <tr>
                                <td>
                                    @if ($product->img)
                                        <img src="{{ asset('storage/' . $product->img) }}"
                                            alt="{{ $product->product_name }}" style="width: 50px; height: 50px;">
                                    @else
                                        No Image
                                    @endif
                                </td>

                                <td>{{ $product->product_sku }}</td>
                                <td>{{ $product->p_description }}(kls)</td>
                                <td>₱ {{ $product->p_price }}</td>

                                <td>{{ $product->created_at->format('F j, Y') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                            data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <ul class="link-list-opt no-bdr">
                                                <li><a href="#"><em class="icon ni ni-edit"></em><span>Edit
                                                            Selected</span></a></li>
                                                <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove
                                                            Selected</span></a></li>
                                                <li><a href="#"><em class="icon ni ni-bar-c"></em><span>Update
                                                            Stock</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('admin.forms.create-product')
    @include('admin.forms.sku-modal')
    @include('admin.forms.updatepricemodal')


</x-app-layout>
