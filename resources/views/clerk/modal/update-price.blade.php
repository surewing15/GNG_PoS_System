<!-- Modal -->
<div class="modal fade" id="updatepriceModal" tabindex="-1" aria-labelledby="updatepriceModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="updatepriceModalLabel">Update Price</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="updatePriceForm" method="POST">
                @csrf
                <input type="hidden" id="stock_id" name="stock_id">
                <div class="modal-body">
                    <!-- Product Name -->
                    <div class="row mt-2 align-items-center">
                        <div class="col-lg-5">
                            <label class="form-label" for="modal_product_name">Product Name</label>
                            <span class="form-note">Product Name</span>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="modal_product_name" name="product_name"
                                    readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Kilos -->
                    <div class="row mt-2 align-items-center">
                        <div class="col-lg-5">
                            <label class="form-label" for="modal_product_kilos">Kilos</label>
                            <span class="form-note">Number of Kilos</span>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-control-wrap">
                                <input type="number" class="form-control" id="modal_product_kilos" name="product_kilos"
                                    readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Price -->
                    <div class="row mt-2 align-items-center">
                        <div class="col-lg-5">
                            <label class="form-label" for="modal_product_price">Price</label>
                            <span class="form-note">Product Price</span>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-control-wrap">
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control" id="modal_product_price"
                                        name="product_price" required step="0.01">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('updatepriceModal');

        if (modal) {
            modal.addEventListener('show.bs.modal', function(event) {
                const button = event.relatedTarget;

                // Set modal values for master stock
                modal.querySelector('#stock_id').value = button.getAttribute('data-stock_id');
                modal.querySelector('#modal_product_name').value = button.getAttribute(
                    'data-product_name');
                modal.querySelector('#modal_product_kilos').value = button.getAttribute(
                    'data-total_kilos'); // Changed from product_kilos to total_kilos
                modal.querySelector('#modal_product_price').value = button.getAttribute(
                    'data-product_price');
            });

            const form = modal.querySelector('#updatePriceForm');
            if (form) {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const stockId = modal.querySelector('#stock_id').value;
                    const newPrice = modal.querySelector('#modal_product_price').value;

                    if (!stockId) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Stock ID is missing!',
                        });
                        return;
                    }

                    if (!newPrice || parseFloat(newPrice) <= 0) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Invalid Input',
                            text: 'Please enter a valid price.',
                        });
                        return;
                    }

                    const submitButton = form.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.innerHTML;
                    submitButton.innerHTML =
                        '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
                    submitButton.disabled = true;

                    try {
                        const formData = new FormData();
                        formData.append('product_price', newPrice);
                        formData.append('_token', document.querySelector('meta[name="csrf-token"]')
                            .content);

                        const response = await fetch(`/stocks/update-price/${stockId}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            },
                            body: formData
                        });

                        const data = await response.json();

                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Success',
                                text: 'Price updated successfully!',
                            }).then(() => {
                                const modalInstance = bootstrap.Modal.getInstance(modal);
                                modalInstance.hide();
                                window.location.reload();
                            });
                        } else {
                            throw new Error(data.message || 'Failed to update price');
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: `Failed to update price: ${error.message}`,
                        });
                    } finally {
                        submitButton.innerHTML = originalButtonText;
                        submitButton.disabled = false;
                    }
                });
            }
        }
    });
</script>
