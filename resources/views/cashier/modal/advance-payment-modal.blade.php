<div class="modal fade" id="advancePaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Process Advance Payment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="advancePaymentForm">
                    <div class="mb-3">
                        <label class="form-label">Customer</label>
                        <div class="input-group">
                            <input type="text" id="advance-customer-search" class="form-control"
                                placeholder="Search customer">
                            <button class="btn btn-outline-primary btn-dim" data-bs-toggle="modal"
                                data-bs-target="#customerModal">Add New</button>
                        </div>
                        <input type="hidden" id="advance-customer-id">
                        <div id="advance-customer-results" class="customer-results-dropdown"></div>
                    </div>

                    <div class="row mb-3">
                        <div class="col">
                            <label class="form-label">Current Balance</label>
                            <input type="text" class="form-control" id="current-balance" disabled>
                        </div>
                        <div class="col">
                            <label class="form-label">Current Advance</label>
                            <input type="text" class="form-control" id="current-advance" disabled>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Amount</label>
                        <div class="input-group">
                            <span class="input-group-text">₱</span>
                            <input type="number" id="advance-amount" class="form-control" step="0.01" min="0"
                                required>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submit-advance-payment">Submit Payment</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const advanceCustomerSearch = document.getElementById('advance-customer-search');
        const advanceCustomerResults = document.getElementById('advance-customer-results');
        const advanceCustomerId = document.getElementById('advance-customer-id');
        let searchTimeout;

        advanceCustomerSearch.addEventListener('input', function() {
            clearTimeout(searchTimeout);
            const query = this.value.trim();

            if (query.length >= 1) {
                searchTimeout = setTimeout(() => {
                    searchAdvanceCustomers(query);
                }, 300);
            } else {
                advanceCustomerResults.style.display = 'none';
            }
        });

        function searchAdvanceCustomers(query) {
            const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

            fetch(`/search-customers/${encodeURIComponent(query)}`, {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.customers.length > 0) {
                        const resultsHtml = data.customers.map(customer => `
                        <div class="customer-item" data-customer-id="${customer.id}">
                            <div class="customer-name">${customer.name}</div>
                            <div class="customer-phone">${customer.phone}</div>
                        </div>
                    `).join('');

                        advanceCustomerResults.innerHTML = resultsHtml;
                        advanceCustomerResults.style.display = 'block';

                        // Add click handlers
                        document.querySelectorAll('.customer-item').forEach(item => {
                            item.addEventListener('click', function() {
                                selectAdvanceCustomer(this);
                            });
                        });
                    } else {
                        advanceCustomerResults.innerHTML =
                            '<div class="customer-item">No customers found</div>';
                        advanceCustomerResults.style.display = 'block';
                    }
                })
                .catch(error => {
                    console.error('Search error:', error);
                    advanceCustomerResults.innerHTML =
                        '<div class="customer-item">Error searching customers</div>';
                    advanceCustomerResults.style.display = 'block';
                });
        }

        async function selectAdvanceCustomer(element) {
            const customerId = element.dataset.customerId;
            const customerName = element.querySelector('.customer-name').textContent;

            advanceCustomerSearch.value = customerName;
            advanceCustomerId.value = customerId;
            advanceCustomerResults.style.display = 'none';

            // Fetch customer balance info
            try {
                const response = await fetch(`/customer/${customerId}/balance-info`);
                const data = await response.json();
                if (data.success) {
                    document.getElementById('current-balance').value = `₱${data.balance.toFixed(2)}`;
                    document.getElementById('current-advance').value =
                        `₱${data.advance_payment.toFixed(2)}`;
                }
            } catch (error) {
                console.error('Error fetching balance:', error);
            }
        }

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!advanceCustomerSearch.contains(e.target) && !advanceCustomerResults.contains(e
                    .target)) {
                advanceCustomerResults.style.display = 'none';
            }
        });

        // Keyboard navigation
        advanceCustomerSearch.addEventListener('keydown', function(e) {
            const items = advanceCustomerResults.querySelectorAll('.customer-item');
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
                    const selectedItem = advanceCustomerResults.querySelector(
                        '.customer-item.selected');
                    if (selectedItem) {
                        selectAdvanceCustomer(selectedItem);
                    }
                    break;

                case 'Escape':
                    advanceCustomerResults.style.display = 'none';
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
    document.getElementById('submit-advance-payment').addEventListener('click', async function() {
        const customerId = document.getElementById('advance-customer-id').value;
        const amount = document.getElementById('advance-amount').value;

        if (!customerId || !amount) {
            Swal.fire({
                icon: 'error',
                title: 'Missing Information',
                text: 'Please select a customer and enter an amount'
            });
            return;
        }

        try {
            const response = await fetch('/advance-payment', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    customer_id: customerId,
                    amount: parseFloat(amount)
                })
            });

            const result = await response.json();

            if (result.success) {
                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Advance payment processed successfully'
                });
                $('#advancePaymentModal').modal('hide');
                document.getElementById('advancePaymentForm').reset();

                // Update balance display
                document.getElementById('current-advance').value = `₱${result.new_balance.toFixed(2)}`;
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: error.message || 'Failed to process advance payment'
            });
        }
    });
</script>

<style>
    .customer-results-dropdown {
        display: none;
        position: absolute;
        width: 100%;
        z-index: 1000;
        background: white;
        border: 1px solid #ddd;
        border-radius: 4px;
        max-height: 200px;
        overflow-y: auto;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    }

    .customer-item {
        padding: 10px 15px;
        border-bottom: 1px solid #eee;
        cursor: pointer;
    }

    .customer-item:hover,
    .customer-item.selected {
        background-color: #f8f9fa;
    }

    .customer-name {
        font-weight: bold;
    }

    .customer-phone {
        font-size: 0.9em;
        color: #666;
    }
</style>
