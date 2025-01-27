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
                                    <th>Kilos</th>
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

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const cartTableBody = document.getElementById('cart-table-body');
            const resetCartButton = document.getElementById('reset-cart');
            const saveCartButton = document.getElementById('save-cart');

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
                        <input type="number" class="form-control kilos-input" value="${productData.kilos}" style="width: 80px; text-align: center;">
                    </td>
                    <td>
                        <input type="number" class="form-control price-input" value="0" style="width: 100px; text-align: center;">
                    </td>
                `;
                        cartTableBody.appendChild(newRow);

                        // Disable the button and apply a "disabled" class
                        event.target.disabled = true;
                        event.target.classList.add('disabled-btn');
                    }
                }
            });
            // Reset the cart
            resetCartButton.addEventListener('click', function() {
                cartTableBody.innerHTML = ''; // Clear all rows

                // Re-enable all "Add to Cart" buttons and remove the "disabled-btn" class
                const addToCartButtons = document.querySelectorAll('.add-to-cart');
                addToCartButtons.forEach(button => {
                    button.disabled = false; // Re-enable the button
                    button.classList.remove('disabled-btn'); // Remove the disabled styling class
                });
            });
            // Save the cart
            saveCartButton.addEventListener('click', function() {
                const cartItems = [];
                const rows = cartTableBody.querySelectorAll('tr');

                rows.forEach(row => {
                    const productId = row.getAttribute('data-id');
                    const kilos = row.querySelector('.kilos-input').value;
                    const price = row.querySelector('.price-input').value;

                    cartItems.push({
                        product_id: productId,
                        kilos: parseFloat(kilos),
                        price: parseFloat(price)
                    });
                });

                fetch('{{ route('stocks.store') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            cart: cartItems
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Saved!',
                                text: 'Stock has been updated successfully.',
                            }).then(() => {
                                location.reload(); // Refresh the page after saving
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

