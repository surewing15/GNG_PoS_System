@extends('admin.theme.layout')

@section('content')
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head d-flex justify-content-between align-items-center">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">Stocks</h4>
            </div>

            <!-- Button to Open Modal -->
            {{-- <div class="nk-block-tools-opt">
                <a href="#" data-bs-toggle="modal" data-bs-target="#stockinModal" class="toggle btn btn-primary">
                    <em class="icon ni ni-plus"></em><span>Stock In</span>
                </a>
            </div> --}}
        </div>

        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <table class="datatable-init-export nowrap table" data-export-title="Export">
                    <thead>
                        <tr>
                            <th>Product Code (SKU)</th>
                            <th>Bags</th>
                            <th>Heads</th>
                            <th>Kilos</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stocks as $stock)
                            <tr>
                                <td>{{ $stock->product->product_sku ?? 'N/A' }}</td>
                                <td></td>
                                <td></td>
                                <td>{{ $stock->total_kilos }}(kls)</td> <!-- Display the summed kilos -->
                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                            data-bs-toggle="dropdown">
                                            <em class="icon ni ni-more-h"></em>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <ul class="link-list-opt no-bdr">

                                                <li><a href="#"><em class="icon ni ni-edit"></em><span>Edit</span></a>
                                                </li>
                                                <li><a href="#"><em
                                                            class="icon ni ni-trash"></em><span>Delete</span></a></li>
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


        <!-- Stock In Modal -->
        <div class="modal fade" id="stockinModal" tabindex="-1" aria-labelledby="stockinModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title fw-bold" id="stockinModalLabel">Create Stocks</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="{{ route('stocks.store') }}">
                            @csrf
                            <div class="form-group mb-3">
                                <label class="form-label" for="product-id">Product Name</label>
                                <select name="product_id" class="form-control" id="product-id" required>
                                    <option value="">Select product</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->product_id }}">{{ $product->product_sku }}</option>
                                    @endforeach
                                </select>

                                @error('product_id')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label" for="kilos">Kilos</label>
                                <input type="number" name="stock_kilos" class="form-control" id="kilos" step="0.01">
                                @error('stock_kilos')
                                    <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>


                            <div class="form-group">
                                <button type="submit" class="btn btn-lg btn-primary">Save Information</button>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer bg-light">
                        <span class="sub-text">Modal Footer Text</span>
                    </div>
                </div>
            </div>
        </div>
    </div> <!-- nk-block -->
@endsection
