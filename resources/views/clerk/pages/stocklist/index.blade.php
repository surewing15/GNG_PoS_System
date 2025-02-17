<x-app-layout>
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head d-flex justify-content-between align-items-center">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">Stock List</h4>
            </div>
        </div>

        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <table class="datatable-init-export nowrap table" data-export-title="Export">
                    <thead>
                        <tr>
                            <th>Delivery receipt(DR)</th>
                            <th>Product (SKU)</th>
                            <th>Total Kilos</th>
                            <th>heads/pcs</th>
                            <th>Price</th>
                            <th>Date</th>
                            <th>Time</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($masterStocks as $stock)
                            <tr>
                                <td>{{ $stock->dr }}</td>
                                <td>{{ $stock->product->product_sku }}</td>
                                <td>{{ $stock->total_all_kilos }}Kg</td>
                                <td>{{ $stock->total_head }}.pcs</td>
                                <td>{{ $stock->price }}</td>
                                <td>{{ \Carbon\Carbon::parse($stock['created_at'])->format('F d, Y') }}</td>
                                <td>{{ \Carbon\Carbon::parse($stock['created_at'])->format('h:i:s A') }}</td>
                                <td>
                                    <div class="dropdown">
                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger"
                                            data-bs-toggle="dropdown">
                                            <em class="icon ni ni-more-h"></em>
                                        </a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <ul class="link-list-opt no-bdr">
                                                <li>
                                                    <a href="javascript:void(0)" data-bs-toggle="modal"
                                                        data-bs-target="#updatepriceModal"
                                                        data-stock_id="{{ $stock->master_stock_id }}"
                                                        data-product_name="{{ $stock->product->product_sku }}"
                                                        data-total_kilos="{{ $stock->total_all_kilos }}"
                                                        data-product_price="{{ $stock->price }}">
                                                        <em class="icon ni ni-eye"></em><span>Edit</span>
                                                    </a>
                                                </li>
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
    @include('clerk.modal.update-price')

    <script>
        $(document).ready(function() {
            $('#updatepriceModal').on('show.bs.modal', function(event) {
                try {
                    var button = $(event.relatedTarget);
                    var productName = button.data('product_name');
                    var totalKilos = button.data('total_kilos');
                    var productPrice = button.data('product_price');

                    if (!productName || !totalKilos || productPrice === undefined) {
                        throw new Error('Missing required data');
                    }

                    var modal = $(this);
                    modal.find('#product_name').val(productName);
                    modal.find('#total_kilos').val(totalKilos);
                    modal.find('#product_price').val(productPrice);

                } catch (error) {
                    console.error('Error:', error);
                    alert('There was an error loading the product data. Please try again.');
                }
            });

            $('#updatepriceModal form').on('submit', function(e) {
                e.preventDefault();
                var price = $('#product_price').val();
                if (!price || price <= 0) {
                    alert('Please enter a valid price');
                    return false;
                }
                this.submit();
            });
        });
    </script>
</x-app-layout>
