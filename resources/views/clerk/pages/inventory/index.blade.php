<x-app-layout>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Current Inventory</h3>
                            </div>
                            <div class="nk-block-head-content">
                                <a href="{{ route('inventory.export') }}" class="btn btn-primary">
                                    <em class="icon ni ni-download"></em>
                                    <span>Export to Excel</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block">
                        <div class="card card-bordered card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner p-0">
                                    <div class="nk-tb-list nk-tb-ulist">
                                        <div class="nk-tb-item nk-tb-head">
                                            <div class="nk-tb-col"><span class="sub-text">Product</span></div>
                                            <div class="nk-tb-col tb-col-md"><span class="sub-text">Total All
                                                    Kilos</span></div>
                                            <div class="nk-tb-col tb-col-md"><span class="sub-text">Total Head</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md"><span class="sub-text">Price</span></div>
                                            <div class="nk-tb-col tb-col-md"><span class="sub-text">DR</span></div>
                                            <div class="nk-tb-col nk-tb-col-tools text-end">
                                                <span class="sub-text">Actions</span>
                                            </div>
                                        </div>
                                        @foreach ($masterStocks as $masterStock)
                                            <div class="nk-tb-item">
                                                <div class="nk-tb-col">
                                                    <div class="user-card">
                                                        <div class="user-avatar bg-primary">
                                                            <span>{{ substr($masterStock->product->product_sku, 0, 2) }}</span>
                                                        </div>
                                                        <div class="user-info">
                                                            <span
                                                                class="tb-lead">{{ $masterStock->product->product_sku }}</span>
                                                            <span>{{ $masterStock->product->category }}</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="nk-tb-col tb-col-md">
                                                    <span>{{ number_format($masterStock->total_all_kilos, 2) }}
                                                        kg</span>
                                                </div>
                                                <div class="nk-tb-col tb-col-md">
                                                    <span>{{ number_format($masterStock->total_head) }}</span>
                                                </div>
                                                <div class="nk-tb-col tb-col-md">
                                                    <span>₱{{ number_format($masterStock->price, 2) }}</span>
                                                </div>
                                                <div class="nk-tb-col tb-col-md">
                                                    <span>{{ $masterStock->dr }}</span>
                                                </div>
                                                <div class="nk-tb-col nk-tb-col-tools">
                                                    <ul class="nk-tb-actions gx-1">
                                                        <li>
                                                            <div class="drodown">
                                                                <a href="#"
                                                                    class="dropdown-toggle btn btn-icon btn-trigger"
                                                                    data-bs-toggle="dropdown">
                                                                    <em class="icon ni ni-more-h"></em>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end">
                                                                    <ul class="link-list-opt no-bdr">
                                                                        <li>
                                                                            <a href="#"
                                                                                onclick="openEditModal({{ $masterStock->master_stock_id }})">
                                                                                <em
                                                                                    class="icon ni ni-edit"></em><span>Edit</span>
                                                                            </a>
                                                                        </li>
                                                                        <li>
                                                                            <form
                                                                                action="{{ route('inventory.destroy', $masterStock->master_stock_id) }}"
                                                                                method="POST" class="delete-form">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <a href="#"
                                                                                    onclick="if(confirm('Are you sure you want to delete this item?')) { this.closest('form').submit(); }">
                                                                                    <em
                                                                                        class="icon ni ni-trash"></em><span>Delete</span>
                                                                                </a>
                                                                            </form>
                                                                        </li>
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Stock</h5>
                    <a href="#" class="close" onclick="closeEditModal()">
                        <em class="icon ni ni-cross"></em>
                    </a>
                </div>
                <div class="modal-body">
                    <form id="editForm" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group">
                            <label class="form-label" for="edit_total_all_kilos">Total All Kilos</label>
                            <div class="form-control-wrap">
                                <input type="number" step="0.01" class="form-control" id="edit_total_all_kilos"
                                    name="total_all_kilos">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="edit_total_head">Total Head</label>
                            <div class="form-control-wrap">
                                <input type="number" class="form-control" id="edit_total_head" name="total_head">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="edit_price">Price</label>
                            <div class="form-control-wrap">
                                <input type="number" step="0.01" class="form-control" id="edit_price"
                                    name="price">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="form-label" for="edit_dr">DR</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="edit_dr" name="dr">
                            </div>
                        </div>
                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Update</button>
                            <button type="button" class="btn btn-light" onclick="closeEditModal()">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openEditModal(stockId) {
            const modal = document.getElementById('editModal');
            const form = document.getElementById('editForm');
            form.action = `/stocks/inventory/${stockId}`;
            new bootstrap.Modal(modal).show();
        }

        function closeEditModal() {
            const modal = document.getElementById('editModal');
            bootstrap.Modal.getInstance(modal).hide();
        }
    </script>
</x-app-layout>
