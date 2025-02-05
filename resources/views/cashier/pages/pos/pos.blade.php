<x-app-layout>

    @if (Auth::user()->role == 'Cashier')
        <div class="row g-3">
            <div class="col-md-8">
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#shortcutsModal">
                    <em class="icon ni ni-keyboard"></em>
                    <span>Shortcuts Guide</span>
                </button>
                <button type="button" class="btn btn-outline-primary" data-bs-toggle="modal"
                    data-bs-target="#advancePaymentModal">
                    <em class="icon ni ni-wallet"></em> Advance Payment
                </button>

                <!-- First Table -->
                <div class="row mb-3">
                    &nbsp
                    <div class="col-md-12">
                        <div class="form-group">
                            <div class="col-sm-12"
                                style="overflow-y: auto; border: 1px solid rgb(241, 0, 0); height: 250px;">
                                <table class="table table-striped table-bordered">
                                    <thead style="background-color: #2c3e50;">
                                        <tr>
                                            <th scope="col" style="text-align: center; width: 15%; color: #FFA500;">
                                                SKU
                                            </th>
                                            <th scope="col" style="text-align: center; width: 15%; color: #FFA500;">
                                                KILOS
                                            </th>
                                            <th scope="col" style="text-align: center; width: 10%; color: #FFA500;">
                                                PRICE
                                            </th>
                                            <th scope="col" style="text-align: center; width: 10%; color: #FFA500;">
                                                SUB-TOTAL
                                            </th>

                                        </tr>
                                    </thead>
                                    <tbody>
                                        <!-- Add rows dynamically here -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Add a button to open the shortcuts guide -->


                <!-- Second Table -->
                <div class="nk-block nk-block-lg">
                    <div class="card card-bordered card-preview">
                        <div class="card-inner">
                            <div style="max-height: 400px; overflow-y: auto;">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th scope="col">SKU</th>
                                            <th scope="col">AVAILABLE STOCKS</th>
                                            <th scope="col">PRICE</th>
                                            <th scope="col">DATE</th>
                                            <th scope="col">Time</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($MasterStocks as $MasterStock)
                                            <tr>
                                                <td>{{ $MasterStock->product->product_sku }}</td>
                                                <td>{{ $MasterStock->total_all_kilos }}</td>
                                                <td>₱{{ number_format($MasterStock->price, 2) }}</td>
                                                <td>{{ \Carbon\Carbon::parse($MasterStock->date)->format('F d, Y') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($MasterStock->created_at)->format('H:i:s') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>


            </div>


            <div class="col-md-4">
                <!-- Total Display -->
                <div class="card mb-3" style="min-height: 350px;">
                    <!-- POS Card -->
                    <div class="nk-content">
                        <div class="container-fluid">
                            <div class="nk-content-inner">
                                <div class="nk-content-body">
                                    <!-- POS Header -->
                                    <div class="card-header bg-primary text-white text-center">
                                        <h4 class="mb-0">Point of Sale</h4>
                                    </div>

                                    <!-- POS Content -->
                                    <div class="card-body">
                                        <div class="d-flex flex-column gap-4">
                                            <!-- Branding -->
                                            <div class="text-center">
                                                <span class="badge badge-primary fs-5 p-2">KAKING</span>
                                            </div>

                                            @include('cashier.modal.advance-payment-modal')
                                            <!-- Customer Selection -->
                                            <div class="input-group">
                                                <input type="text" id="customer-search"
                                                    class="form-control form-control-lg"
                                                    placeholder="Search customer by name or address" autocomplete="off">
                                                <input type="hidden" id="selected-customer-id" name="customer_id"
                                                    required>
                                                <button class="btn btn-outline-primary btn-dim" data-bs-toggle="modal"
                                                    data-bs-target="#customerModal">Add New</button>
                                            </div>

                                            <div id="customer-search-results" class="customer-results-dropdown"></div>


                                            <!-- SKU Input -->
                                            <div class="input-group">
                                                <input type="text" id="sku-input"
                                                    class="form-control form-control-lg" placeholder="Enter SKU"
                                                    autocomplete="off">
                                                <button id="search-sku" class="btn btn-primary">ADD TO CART</button>
                                                <div id="product-details"></div>
                                            </div>

                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Discount Section -->
                <div class="card mb-3" style="background-color: #2c3e50;">
                    <div class="card-body">
                        <!-- Discount Input -->
                        <div class="form-group mb-3">
                            <label for="discount-input" class="text-white-50">Enter Discount (%)</label>
                            <div class="input-group">
                                <input type="number" id="discount-input" class="form-control"
                                    placeholder="Enter Discount Percentage">
                                <button class="btn btn-secondary" data-bs-toggle="modal"
                                    data-bs-target="#discountModal">

                                    <em class="icon ni ni-check"></em> Apply
                                </button>
                            </div>
                        </div>

                        <!-- Discount Amount Display -->
                        <div class="row align-items-center mt-3">
                            <div class="col text-end">
                                <strong class="fs-3 text-white-50">Discount:</strong>
                            </div>
                            <div class="col-auto">
                                <strong id="discount-amount" class="fs-1 text-warning"
                                    style="font-family: 'Digital', monospace;">₱0.00</strong>
                            </div>
                        </div>
                        <div class="row align-items-center">
                            <div class="col text-end">
                                <strong class="fs-3 text-white-50">TOTAL:</strong>
                            </div>
                            <div class="col-auto">
                                <strong id="total" class="fs-1 text-warning"
                                    style="font-family: 'Digital', monospace;">₱0.00</strong>
                            </div>
                        </div>


                    </div>
                </div>


                <div class="card mb-3" style="background-color: #2c3e50;">
                    <div class="card-body">

                        <!-- Total After Discount -->
                        <div class="row align-items-center mt-3">
                            <div class="col text-end">
                                <strong class="fs-3 text-white-50">Total After Discount:</strong>
                            </div>
                            <div class="col-auto">
                                <strong id="total-after-discount" class="fs-1 text-success"
                                    style="font-family: 'Digital', monospace;">₱0.00</strong>
                            </div>
                        </div>
                    </div>
                </div>

                <style>
                    @font-face {
                        font-family: 'Digital';
                        src: url('https://db.onlinewebfonts.com/t/8e22783d707ad140bffe18b2a3812529.woff2') format('woff2');
                    }
                </style>



                <!-- Payment Options -->
                <div class="card-body">
                    <ul class="list-unstyled mb-0">
                        <li class="preview-item d-flex justify-content-center gap-2">
                            <button type="button" class="btn btn-dark" style="height: 60px; font-size: 1.25rem;"
                                data-bs-toggle="modal" data-bs-target="#modalDefault">
                                <em class="icon ni ni-trash"></em> Void All
                            </button>
                            <button type="button" id="void-last-item" class="btn btn-warning"
                                style="height: 60px; font-size: 1.25rem;">
                                <em class="icon ni ni-minus-round"></em> Void Last
                            </button>
                            <button type="button" class="btn btn-primary" style="height: 60px; font-size: 1.25rem;"
                                data-bs-toggle="modal" data-bs-target="#invoiceModal">
                                <em class="icon ni ni-cart-fill"></em> Place Order
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="modal fade" id="editItemModal" tabindex="-1" aria-labelledby="editItemModalLabel"
                aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editItemModalLabel">Edit Item Details</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <form id="editItemForm">
                                <div class="mb-3">
                                    <label for="edit-sku" class="form-label">SKU</label>
                                    <input type="text" class="form-control" id="edit-sku" readonly>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-kilos" class="form-label">Kilos</label>
                                    <input type="number" class="form-control" id="edit-kilos" min="1"
                                        step="0.01" required>
                                    <small class="text-muted">Available stock: <span
                                            id="edit-available-stock">0</span> kg</small>
                                </div>
                                <div class="mb-3">
                                    <label for="edit-price" class="form-label">Price per Kilo (₱)</label>
                                    <input type="number" class="form-control" id="edit-price" min="0"
                                        step="0.01" required>
                                </div>
                            </form>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                            <button type="button" class="btn btn-primary" id="confirmEdit">Confirm</button>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Keyboard Shortcuts Legend Modal -->
            <div class="modal fade" id="shortcutsModal" tabindex="-1" aria-labelledby="shortcutsModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="shortcutsModalLabel">
                                <em class="icon ni ni-keyboard"></em> Keyboard Shortcuts Guide
                            </h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="shortcuts-container">
                                <!-- Shortcut Items -->
                                <div class="shortcut-item mb-3 p-2 border-bottom">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-primary me-2">F2</span>
                                        <strong>Focus SKU Input</strong>
                                    </div>
                                    <small class="text-muted">Quickly jump to SKU entry field and clear existing
                                        dropdown</small>
                                </div>

                                <div class="shortcut-item mb-3 p-2 border-bottom">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-warning me-2">F7</span>
                                        <strong>Void Last Item</strong>
                                    </div>
                                    <small class="text-muted">Remove the last item added to the cart</small>
                                </div>

                                <div class="shortcut-item mb-3 p-2 border-bottom">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-danger me-2">F8</span>
                                        <strong>Void All Items</strong>
                                    </div>
                                    <small class="text-muted">Clear the entire cart (requires confirmation)</small>
                                </div>

                                <div class="shortcut-item mb-3 p-2 border-bottom">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-success me-2">Left Shift</span>
                                        <strong>Place Order</strong>
                                    </div>
                                    <small class="text-muted">Open place order modal (requires items in cart and
                                        selected customer)</small>
                                </div>

                                <div class="shortcut-item mb-3 p-2">
                                    <div class="d-flex align-items-center mb-1">
                                        <span class="badge bg-info me-2">\</span>
                                        <strong>Apply Discount</strong>
                                    </div>
                                    <small class="text-muted">Quick access to discount entry (requires items in
                                        cart)</small>
                                </div>
                            </div>

                            <div class="alert alert-light mt-3">
                                <small>
                                    <em class="icon ni ni-info"></em>
                                    Note: Some shortcuts require specific conditions (e.g., items in cart, customer
                                    selected) to work.
                                </small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @include('cashier.modal.cashier-modal')
        @include('cashier.modal.customer-modal')
        @include('admin.forms.discount-permission-modal')
        <style>
            .input-group {
                position: relative;
                margin-bottom: 15px;
            }

            #product-details {
                position: absolute;
                top: 100%;
                left: 0;
                right: 0;
                background: white;
                border: 1px solid #ddd;
                border-radius: 4px;
                max-height: 200px;
                overflow-y: auto;
                z-index: 1000;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                display: none;
            }

            .product-item {
                padding: 10px 15px;
                border-bottom: 1px solid #eee;
                cursor: pointer;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }

            .product-item:hover {
                background-color: #f8f9fa;
            }

            .product-item:last-child {
                border-bottom: none;
            }

            .customer-results-dropdown {
                display: none;
                position: absolute;
                width: calc(100% - 125px);
                z-index: 1000;
                background: white;
                border: 1px solid #ddd;
                border-radius: 4px;
                max-height: 200px;
                overflow-y: auto;
                box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
                margin-top: 2px;
                scrollbar-width: none;
                /* Firefox */
                -ms-overflow-style: none;
                /* Internet Explorer and Edge */
            }

            /* Hide scrollbar for Chrome, Safari and Opera */
            .customer-results-dropdown::-webkit-scrollbar {
                display: none;
            }

            /* Optional: Add smooth scrolling for a better experience */
            .customer-results-dropdown {
                scroll-behavior: smooth;
            }

            .customer-item {
                padding: 10px 15px;
                border-bottom: 1px solid #eee;
                cursor: pointer;
                transition: background-color 0.2s;
            }

            .customer-item:hover {
                background-color: #f8f9fa;
            }

            .customer-item.selected {
                background-color: #e9ecef;
            }

            .customer-item:last-child {
                border-bottom: none;
            }
        </style>
        <style>
            .shortcuts-container {
                max-height: 400px;
                overflow-y: auto;
            }

            .shortcut-item {
                transition: background-color 0.2s;
            }

            .shortcut-item:hover {
                background-color: #f8f9fa;
            }

            .badge {
                font-family: monospace;
                font-size: 0.9rem;
                padding: 0.4em 0.6em;
            }
        </style>

        <script>
            // Add this to your existing JavaScript
            document.addEventListener('keydown', function(event) {
                // Show shortcuts guide when pressing Ctrl + /
                if (event.ctrlKey && event.key === '/') {
                    event.preventDefault();
                    const shortcutsModal = new bootstrap.Modal(document.getElementById('shortcutsModal'));
                    shortcutsModal.show();
                }
            });
        </script>|
        <script>
            let editModal;
            let currentProduct = null;

            document.addEventListener('DOMContentLoaded', function() {
                editModal = new bootstrap.Modal(document.getElementById('editItemModal'));

                function handleConfirmation() {
                    const kilos = parseFloat(document.getElementById('edit-kilos').value);
                    const price = parseFloat(document.getElementById('edit-price').value);
                    const availableStock = parseFloat(document.getElementById('edit-available-stock').textContent);

                    // Validate inputs
                    if (isNaN(kilos) || kilos <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Quantity',
                            text: 'Please enter a valid quantity.'
                        });
                        return;
                    }

                    if (kilos > availableStock) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Insufficient Stock',
                            text: `Only ${availableStock}kg available.`
                        });
                        return;
                    }

                    if (isNaN(price) || price <= 0) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Invalid Price',
                            text: 'Please enter a valid price.'
                        });
                        return;
                    }

                    // Update the product with edited values
                    if (currentProduct) {
                        currentProduct.stock_kilos = kilos;
                        currentProduct.price = price;
                        addToCartFinal(currentProduct);

                        // Hide the modal before focusing back to SKU input
                        editModal.hide();

                        // Return focus to SKU input after modal closes
                        setTimeout(() => {
                            document.getElementById('sku-input').focus();
                        }, 100);
                    }
                }

                // Single event listener for confirm button
                document.getElementById('confirmEdit').addEventListener('click', handleConfirmation);

                // Handle Enter key on the form - prevent default form submission
                document.getElementById('editItemForm').addEventListener('keydown', function(event) {
                    if (event.key === 'Enter') {
                        event.preventDefault();
                        event.stopPropagation();
                        handleConfirmation();
                    }
                });

                // Prevent Enter key from bubbling up on input fields
                const editInputs = ['edit-sku', 'edit-kilos', 'edit-price'];
                editInputs.forEach(inputId => {
                    document.getElementById(inputId).addEventListener('keydown', function(event) {
                        if (event.key === 'Enter') {
                            event.preventDefault();
                            event.stopPropagation();
                            handleConfirmation();
                        }
                    });
                });
            });

            // Modify your existing addToCart function to show the edit modal first
            function addToCart(product) {
                currentProduct = product;

                // Populate the modal with product details
                document.getElementById('edit-sku').value = product.product_sku;
                document.getElementById('edit-kilos').value = "1";
                document.getElementById('edit-price').value = product.price.toFixed(2);
                document.getElementById('edit-available-stock').textContent = product.stock_kilos;

                // Show the modal
                editModal.show();

                // Focus on the kilos input
                setTimeout(() => {
                    document.getElementById('edit-kilos').focus();
                    document.getElementById('edit-kilos').select();
                }, 500);
            }

            // Create a new function for the final add to cart process
            function addToCartFinal(product) {
                const tbody = document.querySelector('.table-striped tbody');

                if (!product.product_id) {
                    console.error('Product ID is missing:', product);
                    alert('Error: Product ID is missing. Please check the SKU or data.');
                    return;
                }

                // Check if the product is already in the cart with the same price
                const existingRow = Array.from(tbody.querySelectorAll('tr')).find(row => {
                    const rowSku = row.querySelector('th')?.textContent;
                    const rowPrice = parseFloat(row.querySelector('.price-input')?.value);
                    return rowSku === product.product_sku && Math.abs(rowPrice - product.price) < 0.01;
                });

                if (existingRow) {
                    const quantityInput = existingRow.querySelector('.number-spinner');
                    const newQuantity = parseFloat(quantityInput.value) + parseFloat(product.stock_kilos);

                    if (validateStock(existingRow, newQuantity, product.price)) {
                        quantityInput.value = newQuantity;
                        updateSubtotal(existingRow);
                        showAddToCartFeedback(true);
                    } else {
                        showAddToCartFeedback(false);
                    }
                } else {
                    // Add new row to the table with the product details
                    const newRow = document.createElement('tr');
                    newRow.dataset.productId = product.product_id;
                    newRow.dataset.stockKilos = product.stock_kilos;
                    newRow.dataset.price = product.price;

                    newRow.innerHTML = `
                        <th>${product.product_sku}</th>
                        <td style="width: 150px;">
                            <div class="form-control-wrap number-spinner-wrap" style="width: 200px;">
                                <button class="btn btn-icon btn-outline-light number-spinner-btn number-minus" data-number="minus">
                                    <em class="icon ni ni-minus"></em>
                                </button>
                                <input type="number" class="form-control number-spinner" value="${product.stock_kilos}" min="1">
                                <button class="btn btn-icon btn-outline-light number-spinner-btn number-plus" data-number="plus">
                                    <em class="icon ni ni-plus"></em>
                                </button>
                            </div>
                            <div class="stock-info text-danger" style="display: none; font-size: 0.8rem; margin-top: 4px;"></div>
                        </td>
                        <td>
                            <input type="number" class="form-control price-input" value="${product.price.toFixed(2)}" style="width: 150px;" disabled>
                        </td>
                        <td class="subtotal">₱${(product.stock_kilos * product.price).toFixed(2)}</td>
                    `;

                    tbody.appendChild(newRow);
                    setupRowEventListeners(newRow);
                    showAddToCartFeedback(true);
                }

                // Clear the SKU input and reset the product details dropdown
                const skuInput = document.getElementById('sku-input');
                const productDetails = document.getElementById('product-details');
                skuInput.value = '';
                productDetails.style.display = 'none';
                updateCartTotal();

                // Return focus to SKU input
                setTimeout(() => {
                    skuInput.focus();
                }, 100);
            }
        </script>

        <script>
            // Add these styles to highlight the selected item
            const style = document.createElement('style');
            style.textContent = `
    .product-item.selected {
        background-color: #e9ecef;
    }
`;
            document.head.appendChild(style);

            let selectedIndex = -1;
            const skuInput = document.getElementById('sku-input');
            const productDetails = document.getElementById('product-details');
            const searchSkuButton = document.getElementById('search-sku');

            skuInput.addEventListener('keydown', function(event) {
                const items = productDetails.querySelectorAll('.product-item');

                switch (event.key) {
                    case 'ArrowDown':
                        event.preventDefault();
                        if (productDetails.style.display === 'none') {
                            productDetails.style.display = 'block';
                            selectedIndex = 0;
                        } else {
                            selectedIndex = (selectedIndex + 1) % items.length;
                        }
                        updateSelection(items);
                        break;

                    case 'ArrowUp':
                        event.preventDefault();
                        if (productDetails.style.display !== 'none') {
                            selectedIndex = selectedIndex <= 0 ? items.length - 1 : selectedIndex - 1;
                            updateSelection(items);
                        }
                        break;

                    case 'Enter':
                        event.preventDefault();
                        if (selectedIndex >= 0 && items[selectedIndex]) {
                            // If an item is selected in dropdown, add that item
                            items[selectedIndex].click();
                            selectedIndex = -1;
                            // Clear and hide dropdown
                            productDetails.innerHTML = '';
                            productDetails.style.display = 'none';
                        } else {
                            // If no item is selected, trigger the search-sku button click
                            searchSkuButton.click();
                            // Clear and hide dropdown
                            productDetails.innerHTML = '';
                            productDetails.style.display = 'none';
                        }
                        // Clear the input field
                        skuInput.value = '';
                        break;

                    case 'Escape':
                        productDetails.style.display = 'none';
                        productDetails.innerHTML = '';
                        selectedIndex = -1;
                        break;
                }
            });




            function updateSelection(items) {
                items.forEach((item, index) => {
                    item.classList.toggle('selected', index === selectedIndex);
                    if (index === selectedIndex) {
                        item.scrollIntoView({
                            block: 'nearest'
                        });
                    }
                });
            }

            // Reset selection when the dropdown is hidden
            const originalSetupProductItemListeners = setupProductItemListeners;

            function setupProductItemListeners() {
                originalSetupProductItemListeners();
                selectedIndex = -1;
            }

            // SKU Search Functionality
            // Modify SKU search handler to show most recent stock
            document.getElementById('sku-input').addEventListener('keyup', function(event) {
                let query = this.value;
                let productDetails = document.getElementById('product-details');

                if (query.length >= 1) {
                    fetch(`/search-sku/${query}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.products.length > 0) {
                                // Get current cart quantities
                                const cartQuantities = {};
                                Array.from(document.querySelector('.table-striped tbody').querySelectorAll('tr'))
                                    .forEach(row => {
                                        const sku = row.querySelector('th').textContent;
                                        const price = parseFloat(row.querySelector('.price-input').value);
                                        const qty = parseFloat(row.querySelector('.number-spinner').value);
                                        const key = `${sku}-${price}`;
                                        cartQuantities[key] = (cartQuantities[key] || 0) + qty;
                                    });

                                // Filter products with available stock
                                const availableProducts = data.products.filter(product => {
                                    const key = `${product.product_sku}-${product.price}`;
                                    const inCart = cartQuantities[key] || 0;
                                    return (product.stock_kilos - inCart) > 0;
                                });

                                if (availableProducts.length > 0) {
                                    // Sort by created_at in descending order and take the most recent
                                    const mostRecentProduct = availableProducts[
                                        0]; // API already returns sorted by created_at
                                    const key = `${mostRecentProduct.product_sku}-${mostRecentProduct.price}`;
                                    const inCart = cartQuantities[key] || 0;
                                    const availableStock = mostRecentProduct.stock_kilos - inCart;

                                    const productItem = `
                            <div class="product-item" tabindex="0"
                                data-product-id="${mostRecentProduct.product_id}"
                                data-sku="${mostRecentProduct.product_sku}"
                                data-name="${mostRecentProduct.product_name}"
                                data-kilos="${availableStock}"
                                data-price="${mostRecentProduct.price}">
                                <span class="product-sku">${mostRecentProduct.product_sku}</span>
                                <span class="product-price">${availableStock} kg - ₱${parseFloat(mostRecentProduct.price).toFixed(2)}</span>
                            </div>
                        `;

                                    productDetails.innerHTML = productItem;
                                } else {
                                    // If current option is depleted, try to show next available option
                                    const nextAvailableProduct = data.products.find(product => {
                                        const key = `${product.product_sku}-${product.price}`;
                                        const inCart = cartQuantities[key] || 0;
                                        return (product.stock_kilos - inCart) > 0;
                                    });

                                    if (nextAvailableProduct) {
                                        const key =
                                            `${nextAvailableProduct.product_sku}-${nextAvailableProduct.price}`;
                                        const inCart = cartQuantities[key] || 0;
                                        const availableStock = nextAvailableProduct.stock_kilos - inCart;

                                        productDetails.innerHTML = `
                                <div class="product-item" tabindex="0"
                                    data-product-id="${nextAvailableProduct.product_id}"
                                    data-sku="${nextAvailableProduct.product_sku}"
                                    data-name="${nextAvailableProduct.product_name}"
                                    data-kilos="${availableStock}"
                                    data-price="${nextAvailableProduct.price}">
                                    <span class="product-sku">${nextAvailableProduct.product_sku}</span>
                                    <span class="product-price">${availableStock} kg - ₱${parseFloat(nextAvailableProduct.price).toFixed(2)}</span>
                                </div>
                            `;
                                    } else {
                                        productDetails.innerHTML =
                                            '<div class="product-item">No available stock for this SKU</div>';
                                    }
                                }
                                productDetails.style.display = 'block';
                                setupProductItemListeners();
                            } else {
                                productDetails.innerHTML =
                                    '<div class="product-item">No matching products found</div>';
                                productDetails.style.display = 'block';
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            productDetails.innerHTML =
                                '<div class="product-item">Error retrieving product details</div>';
                            productDetails.style.display = 'block';
                        });
                } else {
                    productDetails.style.display = 'none';
                }
            });

            function setupProductItemListeners() {
                const items = document.querySelectorAll('.product-item');
                const skuInput = document.getElementById('sku-input');
                const productDetails = document.getElementById('product-details');

                items.forEach((item) => {
                    item.addEventListener('click', function() {
                        // Skip if this is an error message
                        if (!this.dataset.sku) return;

                        const product = {
                            product_id: this.dataset.productId,
                            product_sku: this.dataset.sku,
                            product_name: this.dataset.name,
                            stock_kilos: parseFloat(this.dataset.kilos),
                            price: parseFloat(this.dataset.price)
                        };

                        skuInput.value = ''; // Clear input
                        productDetails.innerHTML = ''; // Clear dropdown content
                        productDetails.style.display = 'none'; // Hide dropdown
                        showEditModal(product); // Changed from addToCart to showEditModal
                    });
                });
            }

            function showEditModal(product) {
                currentProduct = product;

                // Populate the modal with product details
                document.getElementById('edit-sku').value = product.product_sku;
                document.getElementById('edit-kilos').value = "1";
                document.getElementById('edit-price').value = product.price.toFixed(2);
                document.getElementById('edit-available-stock').textContent = product.stock_kilos;

                // Show the modal
                const editModal = new bootstrap.Modal(document.getElementById('editItemModal'));
                editModal.show();

                // Focus on the kilos input
                setTimeout(() => {
                    document.getElementById('edit-kilos').focus();
                    document.getElementById('edit-kilos').select();
                }, 500);
            }
            // Add to Cart Button Click Handler
            document.getElementById('search-sku').addEventListener('click', function() {
                const skuInput = document.getElementById('sku-input');
                const sku = skuInput.value.trim();
                const productDetails = document.getElementById('product-details');

                // First check if there's a selected product in the dropdown
                const selectedProduct = productDetails.querySelector(`.product-item[data-sku="${sku}"]`);

                if (selectedProduct) {
                    const product = {
                        product_id: selectedProduct.dataset.productId,
                        product_sku: selectedProduct.dataset.sku,
                        product_name: selectedProduct.dataset.name,
                        stock_kilos: selectedProduct.dataset.kilos,
                        price: parseFloat(selectedProduct.dataset.price)
                    };
                    showEditModal(product); // Changed from addToCart to showEditModal
                    skuInput.value = '';
                    productDetails.style.display = 'none';
                } else {
                    // If no product is selected in dropdown, fetch from API
                    fetch(`/search-sku/${sku}`)
                        .then(response => response.json())
                        .then(data => {
                            if (data.success && data.products.length > 0) {
                                const product = data.products[0];
                                if (!product.product_id) {
                                    alert('Product not found or invalid SKU');
                                    return;
                                }
                                showEditModal(product); // Changed from addToCart to showEditModal
                                skuInput.value = '';
                                productDetails.style.display = 'none';
                            } else {
                                alert('Product not found');
                            }
                        })
                        .catch(error => {
                            console.error('Error adding product to cart:', error);
                            alert('Error occurred while adding product to cart');
                        });
                }
            });

            function validateStock(row, newQuantity, price) {
                const sku = row.querySelector('th').textContent;
                const rowPrice = parseFloat(row.querySelector('.price-input').value);

                // Find the matching stock row with the same SKU and price
                const stockRow = Array.from(document.querySelectorAll('.table tbody tr'))
                    .find(tr => {
                        const stockSku = tr.querySelector('td')?.textContent;
                        const stockPrice = parseFloat(tr.querySelector('td:nth-child(3)')?.textContent.replace('₱', ''));
                        return stockSku === sku && Math.abs(stockPrice - rowPrice) < 0.01;
                    });

                const availableStock = stockRow ? parseFloat(stockRow.querySelector('td:nth-child(2)').textContent) : 0;
                const quantityInput = row.querySelector('.number-spinner');
                const stockInfo = row.querySelector('.stock-info');

                if (isNaN(availableStock) || newQuantity > availableStock) {
                    // Show error styling
                    quantityInput.style.borderColor = '#dc3545';
                    quantityInput.style.backgroundColor = '#fff8f8';

                    // Update message to show available stock at different price points
                    const alternateStock = Array.from(document.querySelectorAll('.table tbody tr'))
                        .filter(tr => tr.querySelector('td')?.textContent === sku)
                        .map(tr => {
                            const price = tr.querySelector('td:nth-child(3)').textContent;
                            const stock = tr.querySelector('td:nth-child(2)').textContent;
                            return `${stock} kilos at ${price}`;
                        })
                        .join(', ');

                    stockInfo.textContent = `Insufficient stock `;
                    stockInfo.style.display = 'block';

                    setTimeout(() => {
                        quantityInput.style.borderColor = '';
                        quantityInput.style.backgroundColor = '';
                        stockInfo.style.display = 'none';
                        stockInfo.textContent = '';
                    }, 2000);

                    return false;
                }

                // Reset styles if stock is sufficient
                quantityInput.style.borderColor = '';
                quantityInput.style.backgroundColor = '';
                stockInfo.style.display = 'none';
                stockInfo.textContent = '';

                return true;
            }

            function addToCart(product) {
                const tbody = document.querySelector('.table-striped tbody');

                if (!product.product_id) {
                    console.error('Product ID is missing:', product);
                    alert('Error: Product ID is missing. Please check the SKU or data.');
                    return;
                }

                // Check if the product is already in the cart with the same price
                const existingRow = Array.from(tbody.querySelectorAll('tr')).find(row => {
                    const rowSku = row.querySelector('th')?.textContent;
                    const rowPrice = parseFloat(row.querySelector('.price-input')?.value);
                    return rowSku === product.product_sku && Math.abs(rowPrice - product.price) < 0.01;
                });

                if (existingRow) {
                    const quantityInput = existingRow.querySelector('.number-spinner');
                    const newQuantity = parseInt(quantityInput.value) + 1;

                    if (validateStock(existingRow, newQuantity, product.price)) {
                        quantityInput.value = newQuantity;
                        updateSubtotal(existingRow);
                        showAddToCartFeedback(true);
                    } else {
                        showAddToCartFeedback(false);
                    }
                } else {
                    // Add new row to the table with the product details
                    const newRow = document.createElement('tr');
                    newRow.dataset.productId = product.product_id;
                    newRow.dataset.stockKilos = product.stock_kilos;
                    newRow.dataset.price = product.price;

                    const price = parseFloat(product.price || 0);

                    newRow.innerHTML = `
            <th>${product.product_sku}</th>
            <td style="width: 150px;">
                <div class="form-control-wrap number-spinner-wrap" style="width: 200px;">
                    <button class="btn btn-icon btn-outline-light number-spinner-btn number-minus" data-number="minus">
                        <em class="icon ni ni-minus"></em>
                    </button>
                    <input type="number" class="form-control number-spinner" value="1" min="1">
                    <button class="btn btn-icon btn-outline-light number-spinner-btn number-plus" data-number="plus">
                        <em class="icon ni ni-plus"></em>
                    </button>
                </div>
                <div class="stock-info text-danger" style="display: none; font-size: 0.8rem; margin-top: 4px;"></div>
            </td>
            <td>
                <input type="number" class="form-control price-input" value="${price.toFixed(2)}" style="width: 150px;" disabled>
            </td>
            <td class="subtotal">${price.toFixed(2)}</td>
        `;

                    tbody.appendChild(newRow);
                    setupRowEventListeners(newRow);
                    showAddToCartFeedback(true);
                }

                // Clear the SKU input and reset the product details dropdown
                const skuInput = document.getElementById('sku-input');
                const productDetails = document.getElementById('product-details');
                skuInput.value = '';
                productDetails.style.display = 'none';
                updateCartTotal();

                // Return focus to SKU input
                setTimeout(() => {
                    skuInput.focus();
                }, 100);
            }

            // Add this function right after addToCart
            function showAddToCartFeedback(success) {
                const feedbackEl = document.createElement('div');
                feedbackEl.style.position = 'fixed';
                feedbackEl.style.top = '20px';
                feedbackEl.style.right = '20px';
                feedbackEl.style.padding = '10px 20px';
                feedbackEl.style.borderRadius = '5px';
                feedbackEl.style.zIndex = '9999';

                if (success) {
                    feedbackEl.style.backgroundColor = '#28a745';
                    feedbackEl.style.color = 'white';
                    feedbackEl.textContent = 'Item added to cart';
                } else {
                    feedbackEl.style.backgroundColor = '#dc3545';
                    feedbackEl.style.color = 'white';
                    feedbackEl.textContent = 'Unable to add item';
                }

                document.body.appendChild(feedbackEl);

                // Remove the feedback after 2 seconds
                setTimeout(() => {
                    feedbackEl.remove();
                }, 2000);
            }
            searchSkuButton.addEventListener('click', () => {
                setTimeout(() => {
                    skuInput.focus();
                }, 100);
            });

            function setupRowEventListeners(row) {
                const quantityInput = row.querySelector('.number-spinner');
                const plusBtn = row.querySelector('.number-plus');
                const minusBtn = row.querySelector('.number-minus');
                const currentStock = parseFloat(row.dataset.stockKilos) || 0;

                function updateQuantity(newValue) {
                    if (newValue < 1) newValue = 1;
                    if (validateStock(row, newValue)) {
                        quantityInput.value = newValue;
                        const price = parseFloat(row.querySelector('.price-input').value) || 0;
                        const subtotal = newValue * price;
                        row.querySelector('.subtotal').textContent = `₱${subtotal.toFixed(2)}`; // Make sure to include ₱
                        updateCartTotal();
                    } else {
                        quantityInput.value = currentStock > 0 ? Math.min(currentStock, parseFloat(quantityInput.value)) : 1;
                    }
                }

                // Input change handler
                // In setupRowEventListeners
                quantityInput.addEventListener('change', () => {
                    const newValue = parseFloat(quantityInput.value) || 0;
                    updateQuantity(newValue);
                    updateCartTotal(); // Add this to ensure total updates
                });
                // Plus button handler
                plusBtn.addEventListener('click', () => {
                    updateQuantity(parseInt(quantityInput.value) + 1);
                });

                // Minus button handler
                minusBtn.addEventListener('click', () => {
                    const currentValue = parseInt(quantityInput.value);
                    if (currentValue > 1) {
                        updateQuantity(currentValue - 1);
                    }
                });
            }

            function updateSubtotal(row) {
                const quantity = parseFloat(row.querySelector('.number-spinner').value) || 0;
                const price = parseFloat(row.querySelector('.price-input').value) || 0;
                const subtotal = quantity * price;
                row.querySelector('.subtotal').textContent = formatCurrency(subtotal);
                updateCartTotal();
            }

            // Update cart total calculation
            function updateCartTotal() {
                const subtotals = calculateSubtotal();
                console.log('Subtotals:', subtotals);

                document.getElementById('total').textContent = `₱${subtotals.toFixed(2)}`;
                console.log('Updated total display:', document.getElementById('total').textContent);

                if (currentDiscount > 0) {
                    const discountAmount = (subtotals * currentDiscount) / 100;
                    const totalAfterDiscount = subtotals - discountAmount;

                    document.getElementById('discount-amount').textContent = `₱${discountAmount.toFixed(2)}`;
                    document.getElementById('total-after-discount').textContent = `₱${totalAfterDiscount.toFixed(2)}`;
                    console.log('Discount amount:', discountAmount);
                    console.log('Total after discount:', totalAfterDiscount);
                }
            }
            // Helper function to format currency
            function formatCurrency(amount) {
                return `₱${amount.toFixed(2)}`;
            }
            // Close dropdown when clicking outside
            document.addEventListener('DOMContentLoaded', function() {
                // Clear any existing event listeners for discount input
                const discountInput = document.getElementById('discount-input');
                if (discountInput) {
                    const newDiscountInput = discountInput.cloneNode(true);
                    discountInput.parentNode.replaceChild(newDiscountInput, discountInput);
                    newDiscountInput.addEventListener('change', updateCartTotal);
                }
            });
            // Add this function to handle stock updates
            async function updateStockQuantities(items) {
                try {
                    const response = await fetch('/update-stock', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            items
                        })
                    });

                    const result = await response.json();
                    if (!result.success) {
                        throw new Error(result.message || 'Failed to update stock quantities');
                    }
                    return result;
                } catch (error) {
                    console.error('Stock Update Error:', error);
                    throw error;
                }
            }



            //

            // Initialize global variables
            // Initialize global variables
            let currentDiscount = 0;
            const TEMP_PASSWORD = "admin123"; // Temporary password for demonstration

            // Function to handle discount form submission
            document.getElementById('discount-form').addEventListener('submit', async function(e) {
                e.preventDefault();

                const password = document.getElementById('discount-password').value;
                const newDiscountValue = parseFloat(document.getElementById('discount-input').value) || 0;

                // Temporary password verification
                if (password === TEMP_PASSWORD) {
                    currentDiscount = newDiscountValue; // Only update if password is correct
                    applyDiscount(newDiscountValue);
                    $('#discountModal').modal('hide');
                    // Clear password field
                    document.getElementById('discount-password').value = '';

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Discount Applied',
                        text: `${newDiscountValue}% discount has been applied successfully.`,
                        timer: 2000,
                        showConfirmButton: false
                    });
                } else {
                    // If password is incorrect, reset the discount input to current value
                    document.getElementById('discount-input').value = currentDiscount;
                    updateDiscountDisplay(); // Refresh the display with current discount

                    Swal.fire({
                        icon: 'error',
                        title: 'Authorization Failed',
                        text: 'Invalid authorization code. Please try again.',
                    });
                }
            });

            // Function to apply discount
            function applyDiscount(discountPercentage) {
                const subtotal = calculateSubtotal();
                const discountAmount = (subtotal * discountPercentage) / 100;
                const totalAfterDiscount = subtotal - discountAmount;

                // Update display
                document.getElementById('discount-amount').textContent = `₱${discountAmount.toFixed(2)}`;
                document.getElementById('total-after-discount').textContent = `₱${totalAfterDiscount.toFixed(2)}`;
            }

            // Function to update discount display
            function updateDiscountDisplay() {
                const subtotal = calculateSubtotal();
                const discountAmount = (subtotal * currentDiscount) / 100;
                const totalAfterDiscount = subtotal - discountAmount;

                document.getElementById('discount-amount').textContent = `₱${discountAmount.toFixed(2)}`;
                document.getElementById('total-after-discount').textContent = `₱${totalAfterDiscount.toFixed(2)}`;
            }

            // Function to reset discount
            function resetDiscount() {
                currentDiscount = 0;
                document.getElementById('discount-input').value = '';
                document.getElementById('discount-amount').textContent = '₱0.00';
                document.getElementById('total-after-discount').textContent = '₱0.00';
                document.getElementById('discount-password').value = '';
                $('#discountModal').modal('hide');
                updateCartTotal();
            }

            // Function to calculate subtotal
            function calculateSubtotal() {
                const subtotals = Array.from(document.querySelectorAll('.subtotal'))
                    .map(el => parseFloat(el.textContent.replace('₱', '')) || 0)
                    .reduce((sum, value) => sum + value, 0);
                return subtotals;
            }

            // Update the existing updateCartTotal function to handle discounts
            function updateCartTotal() {
                const subtotals = calculateSubtotal();
                document.getElementById('total').textContent = `₱${subtotals.toFixed(2)}`;

                if (currentDiscount > 0) {
                    const discountAmount = (subtotals * currentDiscount) / 100;
                    const totalAfterDiscount = subtotals - discountAmount;

                    document.getElementById('discount-amount').textContent = `₱${discountAmount.toFixed(2)}`;
                    document.getElementById('total-after-discount').textContent = `₱${totalAfterDiscount.toFixed(2)}`;
                }
            }

            // Event listener for discount input to show modal
            document.querySelector('[data-bs-target="#discountModal"]').addEventListener('click', function() {
                const discountValue = document.getElementById('discount-input').value;
                if (!discountValue || isNaN(discountValue) || discountValue <= 0 || discountValue > 100) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Invalid Discount',
                        text: 'Please enter a valid discount percentage between 0 and 100.',
                    });
                    return;
                }
            });
        </script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const customerSearch = document.getElementById('customer-search');
                const customerSearchResults = document.getElementById('customer-search-results');
                const selectedCustomerId = document.getElementById('selected-customer-id');
                let searchTimeout;

                customerSearch.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    const query = this.value.trim();

                    if (query.length >= 1) {
                        searchTimeout = setTimeout(() => {
                            searchCustomers(query);
                        }, 300); // Add debounce
                    } else {
                        customerSearchResults.style.display = 'none';
                    }
                });

                function searchCustomers(query) {
                    // Get CSRF token from meta tag
                    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                    fetch(`/search-customers/${encodeURIComponent(query)}`, {
                            method: 'GET',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrfToken
                            }
                        })
                        .then(response => {
                            if (!response.ok) {
                                throw new Error('Network response was not ok');
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success && data.customers.length > 0) {
                                const resultsHtml = data.customers.map(customer => `
                        <div class="customer-item" data-customer-id="${customer.id}">
                            <div class="customer-name">${customer.name}</div>
                            <div class="customer-phone">${customer.phone}</div>
                        </div>
                    `).join('');

                                customerSearchResults.innerHTML = resultsHtml;
                                customerSearchResults.style.display = 'block';

                                // Add click handlers to customer items
                                document.querySelectorAll('.customer-item').forEach(item => {
                                    item.addEventListener('click', function() {
                                        selectCustomer(this);
                                    });
                                });
                            } else {
                                customerSearchResults.innerHTML =
                                    '<div class="customer-item">No customers found</div>';
                                customerSearchResults.style.display = 'block';
                            }
                        })
                        .catch(error => {
                            console.error('Search error:', error);
                            customerSearchResults.innerHTML =
                                '<div class="error-message">Error searching customers. Please try again.</div>';
                            customerSearchResults.style.display = 'block';
                        });
                }

                function selectCustomer(element) {
                    const customerId = element.dataset.customerId;
                    const customerName = element.querySelector('.customer-name').textContent;

                    customerSearch.value = customerName;
                    selectedCustomerId.value = customerId;
                    customerSearchResults.style.display = 'none';
                }

                // Close dropdown when clicking outside
                document.addEventListener('click', function(e) {
                    if (!customerSearch.contains(e.target) && !customerSearchResults.contains(e.target)) {
                        customerSearchResults.style.display = 'none';
                    }
                });

                // Keyboard navigation
                customerSearch.addEventListener('keydown', function(e) {
                    const items = customerSearchResults.querySelectorAll('.customer-item');
                    const currentIndex = Array.from(items).findIndex(item => item.classList.contains(
                        'selected'));

                    switch (e.key) {
                        case 'ArrowDown':
                            e.preventDefault();
                            navigateResults(items, currentIndex, 1);
                            break;

                        case 'ArrowUp':
                            e.preventDefault();
                            navigateResults(items, currentIndex, -1);
                            break;

                        case 'Enter':
                            e.preventDefault();
                            const selectedItem = customerSearchResults.querySelector('.customer-item.selected');
                            if (selectedItem) {
                                selectCustomer(selectedItem);
                            } else if (customerSearchResults.firstElementChild) {
                                // If no item is selected but results exist, select the first one
                                selectCustomer(customerSearchResults.firstElementChild);
                            }
                            customerSearchResults.style.display = 'none';
                            break;

                        case 'Escape':
                            customerSearchResults.style.display = 'none';
                            break;
                    }
                });

                function navigateResults(items, currentIndex, direction) {
                    if (items.length === 0) return;

                    items.forEach(item => item.classList.remove('selected'));

                    let newIndex;
                    if (currentIndex === -1) {
                        newIndex = direction > 0 ? 0 : items.length - 1;
                    } else {
                        newIndex = (currentIndex + direction + items.length) % items.length;
                    }

                    items[newIndex].classList.add('selected');
                    items[newIndex].scrollIntoView({
                        block: 'nearest'
                    });
                }

            });
            // Add F2 shortcut to focus SKU input
            // Add F2 and F8 shortcuts
            document.addEventListener('keydown', function(event) {
                // F2 shortcut to focus SKU input
                if (event.key === 'F2') {
                    event.preventDefault(); // Prevent default F2 behavior

                    // Get the SKU input field
                    const skuInput = document.getElementById('sku-input');

                    // Focus the input and select any existing text
                    skuInput.focus();
                    skuInput.select();

                    // Clear any existing dropdown
                    const productDetails = document.getElementById('product-details');
                    if (productDetails) {
                        productDetails.style.display = 'none';
                        productDetails.innerHTML = '';
                    }
                }

                // F8 shortcut to void cart
                if (event.key === 'F8') {
                    event.preventDefault(); // Prevent default F8 behavior

                    // Check if cart is empty
                    const tbody = document.querySelector('.table-striped tbody');
                    if (!tbody.hasChildNodes()) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Empty Cart',
                            text: 'There are no items to void.',
                            confirmButtonColor: '#3085d6'
                        });
                        return;
                    }

                    // Show confirmation dialog
                    Swal.fire({
                        title: 'Void Confirmation',
                        text: 'Are you sure you want to void this transaction?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#dc3545',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, void it!',
                        cancelButtonText: 'Cancel'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Clear the cart
                            tbody.innerHTML = '';

                            // Reset discount
                            resetDiscount();

                            // Reset customer selection
                            const customerSearch = document.getElementById('customer-search');
                            const selectedCustomerId = document.getElementById('selected-customer-id');
                            if (customerSearch) customerSearch.value = '';
                            if (selectedCustomerId) selectedCustomerId.value = '';

                            // Reset totals
                            document.getElementById('total').textContent = '₱0.00';
                            document.getElementById('discount-amount').textContent = '₱0.00';
                            document.getElementById('total-after-discount').textContent = '₱0.00';

                            // Focus back on SKU input
                            const skuInput = document.getElementById('sku-input');
                            if (skuInput) {
                                setTimeout(() => {
                                    skuInput.focus();
                                }, 100);
                            }

                            // Show success message
                            Swal.fire({
                                icon: 'success',
                                title: 'Transaction Voided',
                                text: 'The transaction has been successfully voided.',
                                timer: 1500,
                                showConfirmButton: false
                            });
                        }
                    });
                }
                if (event.key === 'Shift' && event.location === 1) { // location 1 indicates left Shift key
                    event.preventDefault();

                    // Check if cart is empty
                    const tbody = document.querySelector('.table-striped tbody');
                    if (!tbody.hasChildNodes()) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Empty Cart',
                            text: 'Please add items to the cart before placing an order.',
                            confirmButtonColor: '#3085d6'
                        });
                        return;
                    }

                    // Check if customer is selected
                    const selectedCustomerId = document.getElementById('selected-customer-id');
                    if (!selectedCustomerId.value) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Customer Required',
                            text: 'Please select a customer before placing an order.',
                            confirmButtonColor: '#3085d6'
                        });
                        return;
                    }

                    // Trigger the place order modal
                    const placeOrderBtn = document.querySelector('[data-bs-target="#invoiceModal"]');
                    if (placeOrderBtn) {
                        placeOrderBtn.click();
                    }
                }
                document.addEventListener('keydown', function(event) {
                    if (event.key === '\\') { // Check for backslash key
                        event.preventDefault();

                        // Check if cart is empty before allowing discount
                        const tbody = document.querySelector('.table-striped tbody');
                        if (!tbody.hasChildNodes()) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'Empty Cart',
                                text: 'Please add items to the cart before applying a discount.',
                                confirmButtonColor: '#3085d6'
                            });
                            return;
                        }

                        // Prompt for discount percentage using SweetAlert2
                        Swal.fire({
                            title: 'Enter Discount Percentage',
                            input: 'number',
                            inputLabel: 'Discount Percentage',
                            inputAttributes: {
                                min: '0',
                                max: '100',
                                step: '0.01'
                            },
                            showCancelButton: true,
                            confirmButtonText: 'Apply Discount',
                            cancelButtonText: 'Cancel',
                            inputValidator: (value) => {
                                if (!value) {
                                    return 'Please enter a discount percentage';
                                }
                                const discount = parseFloat(value);
                                if (isNaN(discount) || discount < 0 || discount > 100) {
                                    return 'Please enter a valid discount between 0 and 100';
                                }
                            }
                        }).then((result) => {
                            if (result.isConfirmed) {
                                // Set the discount input value
                                document.getElementById('discount-input').value = result.value;

                                // Trigger the discount modal to handle authorization
                                const discountBtn = document.querySelector(
                                    '[data-bs-target="#discountModal"]');
                                if (discountBtn) {
                                    discountBtn.click();
                                }
                            }
                        });
                    }
                });

            });
        </script>

        <script>
            // Void Last Item functionality
            document.getElementById('void-last-item').addEventListener('click', function() {
                const tbody = document.querySelector('.table-striped tbody');
                const rows = tbody.getElementsByTagName('tr');

                if (rows.length === 0) {
                    // Show warning if cart is empty
                    Swal.fire({
                        icon: 'warning',
                        title: 'Empty Cart',
                        text: 'There are no items to void.',
                        confirmButtonColor: '#3085d6'
                    });
                    return;
                }

                // Get the last row
                const lastRow = rows[rows.length - 1];
                const sku = lastRow.querySelector('th').textContent;
                const quantity = lastRow.querySelector('.number-spinner').value;
                const price = lastRow.querySelector('.price-input').value;

                // Show confirmation dialog
                Swal.fire({
                    title: 'Void Last Item',
                    html: `Are you sure you want to void:<br>
              <strong>SKU:</strong> ${sku}<br>
              <strong>Quantity:</strong> ${quantity} kg<br>
              <strong>Price:</strong> ₱${parseFloat(price).toFixed(2)}`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, void it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Remove the last row
                        tbody.removeChild(lastRow);

                        // Update cart totals
                        updateCartTotal();

                        // Show success message
                        Swal.fire({
                            icon: 'success',
                            title: 'Item Voided',
                            text: 'The last item has been removed from the cart.',
                            timer: 1500,
                            showConfirmButton: false
                        });

                        // Focus back on SKU input
                        const skuInput = document.getElementById('sku-input');
                        if (skuInput) {
                            setTimeout(() => {
                                skuInput.focus();
                            }, 100);
                        }
                    }
                });
            });

            // Add keyboard shortcut (F7) for void last item
            document.addEventListener('keydown', function(event) {
                if (event.key === 'F7') {
                    event.preventDefault();
                    document.getElementById('void-last-item').click();
                }
            });
        </script>
    @endif
</x-app-layout>
