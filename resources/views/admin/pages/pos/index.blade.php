@extends('theme.layout')

@section('content')

<div class="row">
    <!-- Left Column -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-body">
                <!-- Product Category and Count Selection -->
                <div class="row">
                    <!-- Product Category -->
                    <div class="col-md-6">
                        <div class="form-control-wrap">
                            <label class="form-label" for="p_category">Product Category</label>
                            <div class="input-group">
                                <select class="form-select" name="p_category" id="p_category" required>
                                    <option disabled selected>Select Category</option>
                                    <option value="lobot">Feet</option>
                                    <option value="sampot">Wings</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Product Count -->
                    <div class="col-md-6">
                        <div class="form-control-wrap">
                            <label class="form-label" for="p_count">Product Count</label>
                            <div class="input-group">
                                <select class="form-select" name="p_count" id="p_count" required>
                                    <option disabled selected>Select Count</option>
                                    <option value="10">10 Products</option>
                                    <option value="20">20 Products</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-xxl-3 col-lg-4 col-sm-6">
                        <div class="card card-bordered product-card">
                            <div class="product-thumb">
                                <a>
                                    <img class="card-img-top" src="./images/product/lg-a.jpg" alt="Product Image">
                                </a>
                                <ul class="product-badges">
                                    <li><span class="badge bg-success">Stock: 17</span></li>
                                </ul>
                            </div>
                            <div class="card-inner text-center">
                                <ul class="product-tags">
                                    <li><a href="#">SKU</a></li>
                                </ul>
                                <h5 class="product-title"><a href="#">P1</a></h5>
                                <div class="product-price text-primary h5">$324</div>
                                <!-- Add Button -->
                                <a href="#" class="btn btn-primary mt-2">Add</a> <!-- Button to add the product -->
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>

  <!-- Right Column -->
<div class="col-md-5">
    <div class="card">
        <div class="card-body">
            <div class="form-group">
                <label for="customer_id">Customer <span class="text-danger">*</span></label>
                <div class="input-group">
                    <a href="http://127.0.0.1:8000/customers/create" class="btn btn-primary">
                        <i class="bi bi-person-plus"></i>
                    </a>
                    <select wire:model.live="customer_id" id="customer_id" class="form-select">
                        <option value="" selected>Select Customer</option>
                        <option value="1">antony Anton</option>
                    </select>
                </div>
            </div>

            <div class="table-responsive mt-3">
                <table class="table">
                    <thead>
                        <tr class="text-center">
                            <th class="align-middle">Product</th>
                            <th class="align-middle">Price</th>
                            <th class="align-middle">Quantity</th>
                            <th class="align-middle">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="4" class="text-center">
                                <span class="text-danger">Please search & select products!</span>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="table-responsive mt-3">
                <table class="table table-striped">
                    <tbody>
                        <tr>
                            <th>Order Tax (0%)</th>
                            <td>(+) $0.00</td>
                        </tr>
                        <tr>
                            <th>Discount (0%)</th>
                            <td>(-) $0.00</td>
                        </tr>
                        <tr>
                            <th>Shipping</th>
                            <input type="hidden" value="0" name="shipping_amount">
                            <td>(+) $0.00</td>
                        </tr>
                        <tr class="text-primary">
                            <th>Grand Total</th>
                            <td>(=) $0.00</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="row mt-3">
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="tax_percentage">Order Tax (%)</label>
                        <input wire:model.blur="global_tax" type="number" class="form-control" min="0" max="100" value="0" required>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="discount_percentage">Discount (%)</label>
                        <input wire:model.blur="global_discount" type="number" class="form-control" min="0" max="100" value="0" required>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="form-group">
                        <label for="shipping_amount">Shipping</label>
                        <input wire:model.blur="shipping" type="number" class="form-control" min="0" value="0" required step="0.01">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-center flex-wrap mb-0 mt-3">
                <button wire:click="resetCart" type="button" class="btn btn-danger me-3">
                    <i class="bi bi-x"></i> Reset
                </button>
                <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#checkoutModal">Check Out</button>

            </div>
        </div>
    </div>
</div>
@include('forms.checkout')
@endsection
