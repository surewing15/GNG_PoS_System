<x-app-layout>
    <div class="row">
        <!-- Products Section -->
        <div class="col-md-7">
            <div class="card">
                <div class="card-body">
                    <!-- Filter and Product Count -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-control-wrap">
                                <label class="form-label" for="category">Filter by Category</label>
                                <div class="input-group">
                                    <select class="form-select" name="category" id="category">
                                        <option value="">Select</option>
                                        <option value="">Whole Sale</option>
                                        <option value="">By Product</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- Product Search and Display -->
                    <div class="row mt-3">
                        <div class="col-12 mb-3">
                            <div class="form-control-wrap">
                                <input type="text" id="product-search" class="form-control form-control-lg"
                                    placeholder="Search products...">
                            </div>
                        </div>
                        @foreach ($products as $product)
                            <div class="col-xxl-3 col-lg-4 col-sm-6">
                                <div class="card card-bordered product-card">
                                    <div class="card-inner text-center">
                                        <ul class="product-tags">
                                            <li><a href="#">{{ $product->sku }}</a></li>
                                        </ul>
                                        <h5 class="product-title">{{ $product->product_sku }}</h5>
                                        <button class="btn btn-primary mt-2 add-to-cart"
                                            data-product='@json(['id' => $product->product_id, 'name' => $product->product_sku, 'kilos' => 1])'>
                                            Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>

        <!-- Cart Section -->
        <div class="col-md-5">
            <div class="card mb-4">
                <div class="card-body">
                    <div class="table-responsive mt-3">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Product (SKU)</th>
                                    <th>Kilos/Tray</th>
                                    <th>Head/Pcs</th>
                                    <th>Price</th>
                                </tr>
                            </thead>
                            <tbody id="cart-table-body">
                                <!-- Cart items will be dynamically added here -->
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center flex-wrap mb-0 mt-3">
                        <button type="button" class="btn btn-secondary me-2" id="save-cart">Save</button>
                        <button type="button" class="btn btn-danger" id="reset-cart">Reset</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="drModal" tabindex="-1" aria-labelledby="drModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="drModalLabel">Enter Delivery Receipt Number</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="dr-number" class="form-label">DR Number</label>
                            <input type="text" class="form-control" id="dr-number" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="confirm-save">Confirm Save</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartTableBody = document.getElementById('cart-table-body');
            const saveCartButton = document.getElementById('save-cart');
            const resetCartButton = document.getElementById('reset-cart');
            const drModal = new bootstrap.Modal(document.getElementById('drModal'));
            const confirmSaveButton = document.getElementById('confirm-save');
            let cartItemsToSave = [];

            // Function to format number to 2 decimal places
            function formatDecimal(value) {
                return parseFloat(value || 0).toFixed(2);
            }

            // Function to handle input formatting
            function handleInputFormat(input) {
                input.addEventListener('blur', function() {
                    this.value = formatDecimal(this.value);
                });

                // Prevent more than 2 decimal places while typing
                input.addEventListener('input', function() {
                    if (this.value.includes('.')) {
                        const parts = this.value.split('.');
                        if (parts[1] && parts[1].length > 2) {
                            this.value = parseFloat(this.value).toFixed(2);
                        }
                    }
                });
            }

            document.addEventListener('click', function(event) {
                if (event.target.classList.contains('add-to-cart')) {
                    const productData = JSON.parse(event.target.getAttribute('data-product'));
                    const productId = productData.id;

                    let existingRow = cartTableBody.querySelector(`tr[data-id="${productId}"]`);

                    if (existingRow) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Product Already in Cart',
                            text: `${productData.name} is already in the cart.`,
                            confirmButtonText: 'Okay',
                        });
                    } else {
                        const newRow = document.createElement('tr');
                        newRow.setAttribute('data-id', productId);
                        newRow.innerHTML = `
                    <td>${productData.name}</td>
                    <td>
                        <input type="number" step="0.01" class="form-control kilos-input" value="${formatDecimal(productData.kilos)}"
                            style="width: 80px; text-align: center;">
                    </td>
                    <td>
                        <input type="number" step="0.01" class="form-control head-input" value="0.00"
                            style="width: 80px; text-align: center;">
                    </td>
                    <td>
                        <input type="number" step="0.01" class="form-control price-input" value="0.00"
                            style="width: 100px; text-align: center;">
                    </td>
                `;
                        cartTableBody.appendChild(newRow);

                        // Add formatting handlers to new inputs
                        const inputs = newRow.querySelectorAll('input[type="number"]');
                        inputs.forEach(handleInputFormat);

                        event.target.disabled = true;
                        event.target.classList.add('disabled-btn');
                    }
                }
            });

            resetCartButton.addEventListener('click', function() {
                cartTableBody.innerHTML = '';
                document.querySelectorAll('.add-to-cart').forEach(button => {
                    button.disabled = false;
                    button.classList.remove('disabled-btn');
                });
            });

            saveCartButton.addEventListener('click', function() {
                const rows = cartTableBody.querySelectorAll('tr');

                if (rows.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Empty Cart',
                        text: 'Please add items to the cart before saving.',
                    });
                    return;
                }

                cartItemsToSave = [];
                let isValid = true;

                rows.forEach(row => {
                    const productId = row.getAttribute('data-id');
                    const kilos = row.querySelector('.kilos-input').value;
                    const head = row.querySelector('.head-input').value;
                    const price = row.querySelector('.price-input').value;

                    if (!kilos || !price) {
                        isValid = false;
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Input',
                            text: 'Please fill in all required fields (kilos and price) for each item.',
                        });
                        return;
                    }

                    cartItemsToSave.push({
                        product_id: productId,
                        kilos: parseFloat(kilos),
                        head: parseFloat(head || 0),
                        price: parseFloat(price)
                    });
                });

                if (isValid) {
                    drModal.show();
                }
            });

            confirmSaveButton.addEventListener('click', function() {
                const drNumber = document.getElementById('dr-number').value.trim();

                if (!drNumber) {
                    Swal.fire({
                        icon: 'error',
                        title: 'DR Number Required',
                        text: 'Please enter a delivery receipt number.',
                    });
                    return;
                }

                fetch('{{ route('stocks.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            cart: cartItemsToSave,
                            dr: drNumber
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        drModal.hide();
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Saved!',
                                text: 'Stock has been updated successfully.',
                            }).then(() => {
                                location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: data.message || 'Failed to save stock.',
                            });
                        }
                    })
                    .catch(error => {
                        drModal.hide();
                        console.error('Save error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'An unexpected error occurred.',
                        });
                    });
            });
        });
    </script>
</x-app-layout>
