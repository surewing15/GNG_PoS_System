<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true"
    data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceModalLabel">Receipt</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Receipt Content -->
                <div class="receipt text-center">
                    <!-- Logo -->
                    <div class="receipt-brand mb-3" style="display: flex; justify-content: center;">
                        <img src="/storage/logo.png" alt="Logo" style="max-width: 100px;">
                    </div>


                    <!-- Header -->
                    <div class="receipt-header mb-4">
                        <h4 class="mb-3">Thank you for your purchase!</h4>
                        <p class="mb-1"><strong>Customer Name:</strong> <span id="customerName"></span></p>
                        <p class="mb-1"><strong>Date:</strong> <span id="receiptDate"></span></p>
                        <p class="mb-3"><strong>Receipt #:</strong> <span id="receiptID"></span></p>
                    </div>

                    <!-- Service Type -->
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label"><strong>Service Type:</strong></label>
                            <select id="serviceType" class="form-select">
                                <option value="walkin">Walk-In</option>
                                <option value="deliver">Deliver</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label"><strong>Payment Type:</strong></label>
                            <select id="paymentType" class="form-select">
                                <option value="cash">Cash</option>
                                <option value="debit">Debit</option>
                                <option value="online">Online Payment</option>
                                <option value="advance_payment">Advance Payment</option>
                            </select>
                        </div>
                    </div>
                    <div id="advancePaymentInfo" class="mb-3" style="display: none;">
                        <div class="card">
                            <div class="card-body">
                                <h6 class="card-title">Advance Payment Details</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <p class="mb-2"><strong>Available Balance:</strong></p>
                                        <p class="mb-2" id="availableAdvance">₱0.00</p>
                                    </div>
                                    <div class="col-md-6">
                                        <p class="mb-2"><strong>Amount to Use:</strong></p>
                                        <p class="mb-2" id="advanceToUse">₱0.00</p>
                                    </div>
                                </div>
                                <div id="insufficientAdvanceWarning" class="alert alert-warning mt-2"
                                    style="display: none;">
                                    Insufficient advance payment. Additional payment required.
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" id="referenceNumberContainer" style="display: none;">
                        <label class="form-label"><strong>Reference Number:</strong></label>
                        <input type="text" class="form-control" id="reference-number"
                            placeholder="Enter reference number">
                    </div>

                    <!-- Items Table -->
                    <div class="receipt-items mb-4">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>SKU</th>
                                    <th class="text-end">Kilos</th>
                                    <th class="text-end">Price</th>
                                    <th class="text-end">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Items will be inserted here -->
                            </tbody>
                        </table>
                    </div>

                    <!-- Totals -->
                    <div class="receipt-total text-end">
                        <!-- Totals will be inserted here -->
                    </div>

                    <div class="payment-section mt-4 mb-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label"><strong>Amount Paid:</strong></label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="number" class="form-control" id="amount-paid" step="0.01"
                                        min="0">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label"><strong>Change:</strong></label>
                                <div class="input-group">
                                    <span class="input-group-text">₱</span>
                                    <input type="text" class="form-control" id="change-amount" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Footer -->
                    <div class="receipt-footer mt-4">
                        <p class="text-muted small">This receipt was generated electronically and is valid without
                            signature.</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                {{-- <a href="/cashier/trucking/create" class="btn btn-dark">Deliver</a> --}}

                <button type="button" class="btn btn-success" onclick="confirmPayment()">Confirm</button>
                <button type="button" class="btn btn-primary print-receipt">
                    <i class="fas fa-print"></i> Print
                </button>
            </div>
        </div>
    </div>
</div>
<style>
    body.modal-open {
        overflow: auto !important;
        padding-right: 0 !important;
    }

    .modal-backdrop {
        position: fixed;
        z-index: 1040;
    }

    .modal {
        z-index: 1050;
    }
</style>
<script>
    let isSubmitting = false;
    // Global UI Reset Function
    function resetUIState() {
        // Reset all form inputs
        const formInputs = document.querySelectorAll('input');
        formInputs.forEach(input => {
            if (input.type === 'text' || input.type === 'number') {
                input.value = '';
            }
        });

        // Reset customer search and hidden input
        document.getElementById('customer-search').value = '';
        document.getElementById('selected-customer-id').value = '';
        document.getElementById('paymentType').value = 'cash';

        // Reset any active modals
        const modals = document.querySelectorAll('.modal');
        modals.forEach(modal => {
            const modalInstance = bootstrap.Modal.getInstance(modal);
            if (modalInstance) {
                modalInstance.hide();
            }
        });

        // Remove modal backdrop if present
        const backdrop = document.querySelector('.modal-backdrop');
        if (backdrop) {
            backdrop.remove();
        }

        // Reset body classes
        document.body.classList.remove('modal-open');
        document.body.style.overflow = '';
        document.body.style.paddingRight = '';

        // Reset current discount
        window.currentDiscount = 0;
    }

    document.addEventListener('DOMContentLoaded', function() {
        const placeOrderBtn = document.querySelector('button[data-bs-target="#invoiceModal"]');
        const invoiceModal = document.getElementById('invoiceModal');

        // Modal Cleanup Function
        function cleanupModal() {
            const modal = bootstrap.Modal.getInstance(invoiceModal);
            if (modal) {
                modal.dispose();
            }
            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }
            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';
        }

        // Validation Function
        function validateOrder() {
            const customerInput = document.getElementById('customer-search');
            const selectedCustomerId = document.getElementById('selected-customer-id');

            if (!customerInput.value || !selectedCustomerId.value) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Customer Required',
                    text: 'Please select a customer before placing an order.'
                });
                return false;
            }

            const cartItems = document.querySelector('.table-striped tbody').children;
            if (cartItems.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Empty Cart',
                    text: 'Please add items to the cart before placing an order.'
                });
                return false;
            }
            return true;
        }

        // Receipt Generation Functions
        function generateReceiptID() {
            const timestamp = Date.now().toString(36);
            const random = Math.random().toString(36).substring(2, 7).toUpperCase();
            return `RCP-${random}`;
        }

        function formatDate(date) {
            return new Intl.DateTimeFormat('en-US', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            }).format(date);
        }

        // Receipt Items Update Function
        function updateReceiptItems() {
            const receiptTbody = document.querySelector('#invoiceModal .receipt-items tbody');
            const cartItems = document.querySelectorAll('.table-striped tbody tr');

            if (!receiptTbody) {
                console.error('Receipt tbody not found');
                return;
            }

            receiptTbody.innerHTML = '';
            const aggregatedItems = {};

            cartItems.forEach((item) => {
                try {
                    const sku = item.querySelector('th')?.textContent?.trim();
                    const kilos = parseFloat(item.querySelector('.number-spinner')?.value) || 0;
                    const price = parseFloat(item.querySelector('.price-input')?.value) || 0;
                    const total = parseFloat(item.querySelector('.subtotal')?.textContent?.replace('₱',
                        '')) || 0;

                    if (sku && kilos > 0 && price > 0 && total > 0) {
                        if (aggregatedItems[sku]) {
                            aggregatedItems[sku].kilos += kilos;
                            aggregatedItems[sku].total += total;
                        } else {
                            aggregatedItems[sku] = {
                                kilos,
                                price,
                                total
                            };
                        }
                    }
                } catch (error) {
                    console.error('Error processing item for receipt:', error);
                }
            });

            Object.entries(aggregatedItems).forEach(([sku, {
                kilos,
                price,
                total
            }]) => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="text-start">${sku}</td>
                    <td class="text-end">${kilos.toFixed(2)}</td>
                    <td class="text-end">₱${price.toFixed(2)}</td>
                    <td class="text-end">₱${total.toFixed(2)}</td>
                `;
                receiptTbody.appendChild(row);
            });
        }

        // Modal Content Update Function
        function updateModalContent() {
            try {
                const customerName = document.getElementById('customer-search').value;
                if (!customerName) {
                    console.error('No customer selected');
                    return;
                }

                document.getElementById('receiptDate').textContent = formatDate(new Date());
                document.getElementById('receiptID').textContent = generateReceiptID();
                document.getElementById('customerName').textContent = customerName;

                updateReceiptItems();

                const subtotal = document.getElementById('total').textContent;
                const discountAmount = document.getElementById('discount-amount').textContent;
                const totalAfterDiscount = document.getElementById('total-after-discount').textContent;

                const subtotalValue = parseFloat(subtotal.replace('₱', '')) || 0;
                const discountValue = parseFloat(discountAmount.replace('₱', '')) || 0;
                const grandTotal = discountValue > 0 ?
                    parseFloat(totalAfterDiscount.replace('₱', '')) || 0 :
                    subtotalValue;

                const receiptTotal = document.querySelector('.receipt-total');
                if (receiptTotal) {
                    receiptTotal.innerHTML = `
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Subtotal:</strong>
                            <span>${subtotal}</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <strong>Discount:</strong>
                            <span>${discountAmount}</span>
                        </div>
                        <div class="d-flex justify-content-between fw-bold">
                            <strong>GRAND TOTAL:</strong>
                            <span>₱${grandTotal.toFixed(2)}</span>
                        </div>
                    `;
                }

                const amountPaidInput = document.getElementById('amount-paid');
                if (amountPaidInput.value) {
                    calculateChange();
                }
            } catch (error) {
                console.error('Error updating modal content:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to update receipt content'
                });
            }
        }

        // UI Reset Function
        function resetUIState() {
            const formInputs = document.querySelectorAll('input');
            formInputs.forEach(input => {
                if (input.type === 'text' || input.type === 'number') {
                    input.value = '';
                }
            });

            document.getElementById('customer-search').value = '';
            document.getElementById('selected-customer-id').value = '';
            document.getElementById('paymentType').value = 'cash';

            const modals = document.querySelectorAll('.modal');
            modals.forEach(modal => {
                const modalInstance = bootstrap.Modal.getInstance(modal);
                if (modalInstance) {
                    modalInstance.hide();
                }
            });

            const backdrop = document.querySelector('.modal-backdrop');
            if (backdrop) {
                backdrop.remove();
            }

            document.body.classList.remove('modal-open');
            document.body.style.overflow = '';
            document.body.style.paddingRight = '';

            window.currentDiscount = 0;
        }

        // Event Listeners
        if (placeOrderBtn) {
            placeOrderBtn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                if (validateOrder()) {
                    const modal = new bootstrap.Modal(invoiceModal);
                    updateModalContent();
                    modal.show();
                }
            });
        }

        document.querySelector('.print-receipt').addEventListener('click', async function() {
            try {
                const receiptData = {
                    receipt_id: document.getElementById('receiptID').textContent,
                    customer_name: document.getElementById('customerName').textContent,
                    service_type: document.getElementById('serviceType').value,
                    payment_type: document.getElementById('paymentType').value,
                    items: Array.from(document.querySelectorAll('.receipt-items tbody tr')).map(
                        row => ({
                            sku: row.cells[0].textContent,
                            kilos: parseFloat(row.cells[1].textContent),
                            price_per_kilo: parseFloat(row.cells[2].textContent.replace(
                                '₱', '')),
                            total: parseFloat(row.cells[3].textContent.replace('₱', ''))
                        })),
                    subtotal: parseFloat(document.querySelector(
                        '.receipt-total .mb-2:first-child span').textContent.replace(
                        '₱', '')),
                    discount_amount: parseFloat(document.querySelector(
                        '.receipt-total .mb-2:nth-child(2) span').textContent.replace(
                        '₱', '')),
                    total_amount: parseFloat(document.querySelector(
                        '.receipt-total .fw-bold span').textContent.replace('₱', ''))
                };

                // Add payment-specific data
                switch (receiptData.payment_type) {
                    case 'cash':
                        receiptData.amount_paid = parseFloat(document.getElementById('amount-paid')
                            .value);
                        receiptData.change_amount = parseFloat(document.getElementById(
                            'change-amount').value);
                        break;
                    case 'advance_payment':
                        receiptData.used_advance_payment = parseFloat(document.getElementById(
                            'advanceToUse').textContent.replace('₱', ''));
                        break;
                    case 'online':
                        receiptData.reference_number = document.getElementById('reference-number')
                            .value;
                        break;
                }

                const response = await fetch('/cashier/print-receipt', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .content
                    },
                    body: JSON.stringify(receiptData)
                });

                const result = await response.json();
                if (!result.success) {
                    throw new Error(result.message || 'Print failed');
                }

                Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Receipt printed successfully'
                });

            } catch (error) {
                console.error('Print error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Print Error',
                    text: error.message || 'Failed to print receipt'
                });
            }
        });

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                const modal = bootstrap.Modal.getInstance(invoiceModal);
                if (modal) {
                    modal.hide();
                    cleanupModal();
                }
            }
        });

        invoiceModal.addEventListener('hidden.bs.modal', cleanupModal);
    });


    document.addEventListener('DOMContentLoaded', function() {
        const paymentTypeSelect = document.getElementById('paymentType');
        const paymentSection = document.querySelector('.payment-section');
        const referenceNumberContainer = document.getElementById('referenceNumberContainer');
        const advancePaymentInfo = document.getElementById('advancePaymentInfo');
        const availableAdvanceElement = document.getElementById('availableAdvance');
        const advanceToUseElement = document.getElementById('advanceToUse');
        const insufficientAdvanceWarning = document.getElementById('insufficientAdvanceWarning');
        let currentAdvancePayment = 0;

        async function fetchCustomerBalance(customerId) {
            try {
                const response = await fetch(`/cashier/customer-balance/${customerId}`);
                const data = await response.json();

                if (data.success) {
                    currentAdvancePayment = parseFloat(data.advance_payment);
                    return currentAdvancePayment;
                }
                return 0;
            } catch (error) {
                console.error('Error fetching balance:', error);
                return 0;
            }
        }

        function updateAdvancePaymentDisplay(availableAdvance, grandTotal) {
            const amountToUse = Math.min(availableAdvance, grandTotal);
            availableAdvanceElement.textContent = `₱${availableAdvance.toFixed(2)}`;
            advanceToUseElement.textContent = `₱${amountToUse.toFixed(2)}`;

            if (availableAdvance < grandTotal) {
                insufficientAdvanceWarning.style.display = 'block';
                insufficientAdvanceWarning.textContent =
                    `Insufficient advance payment. Additional payment of ₱${(grandTotal - availableAdvance).toFixed(2)} required.`;
            } else {
                insufficientAdvanceWarning.style.display = 'none';
            }

            // Update amount paid input
            document.getElementById('amount-paid').value = amountToUse.toFixed(2);
            document.getElementById('change-amount').value = '0.00';
        }

        async function handlePaymentTypeChange() {
            const selectedValue = paymentTypeSelect.value;
            const customerId = document.getElementById('selected-customer-id').value;
            const grandTotal = parseFloat(document.querySelector('.receipt-total .fw-bold span')
                .textContent.replace('₱', '')) || 0;

            // Reset displays
            paymentSection.style.display = 'block';
            referenceNumberContainer.style.display = 'none';
            advancePaymentInfo.style.display = 'none';

            if (selectedValue === 'advance_payment') {
                if (!customerId) {
                    alert('Please select a customer first');
                    paymentTypeSelect.value = 'cash';
                    return;
                }

                const availableAdvance = await fetchCustomerBalance(customerId);
                advancePaymentInfo.style.display = 'block';
                paymentSection.style.display = 'none';
                updateAdvancePaymentDisplay(availableAdvance, grandTotal);

            } else if (selectedValue === 'online') {
                referenceNumberContainer.style.display = 'block';
                paymentSection.style.display = 'none';
            } else if (selectedValue === 'debit') {
                paymentSection.style.display = 'none';
            }
        }

        // Add event listeners
        if (paymentTypeSelect) {
            paymentTypeSelect.addEventListener('change', handlePaymentTypeChange);
        }

        // Modify the existing confirmPayment function
        window.confirmPayment = async function() {
            if (isSubmitting) return;

            const confirmButton = document.querySelector('button.btn-success');
            const paymentType = document.getElementById('paymentType').value;
            const amountPaid = parseFloat(document.getElementById('amount-paid').value) || 0;
            const totalAmount = parseFloat(document.querySelector('.receipt-total .fw-bold span')
                .textContent.replace('₱', '')) || 0;
            const customerId = document.getElementById('selected-customer-id').value;

            try {
                isSubmitting = true;
                confirmButton.disabled = true;

                // Validate based on payment type
                switch (paymentType) {
                    case 'advance_payment':
                        const availableAdvance = parseFloat(document.getElementById('availableAdvance')
                            .textContent.replace('₱', '')) || 0;
                        const advanceToUse = parseFloat(document.getElementById('advanceToUse')
                            .textContent.replace('₱', '')) || 0;

                        if (availableAdvance < advanceToUse) {
                            throw new Error('Insufficient advance payment available.');
                        }
                        break;

                    case 'online':
                        const referenceNumber = document.getElementById('reference-number').value;
                        if (!referenceNumber.trim()) {
                            throw new Error('Please enter a reference number for online payment.');
                        }
                        break;

                    case 'cash':
                        if (amountPaid <= 0) {
                            throw new Error('Amount paid must be greater than 0');
                        }

                        // Calculate remaining balance
                        const remainingBalance = totalAmount - amountPaid;
                        if (remainingBalance > 0) {
                            // Show confirmation for partial payment
                            const confirmPartial = await Swal.fire({
                                title: 'Partial Payment',
                                text: `₱${remainingBalance.toFixed(2)} will be added to customer's balance. Continue?`,
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonText: 'Yes, continue',
                                cancelButtonText: 'No, cancel'
                            });

                            if (!confirmPartial.isConfirmed) {
                                isSubmitting = false;
                                confirmButton.disabled = false;
                                return;
                            }
                        }
                        break;
                }

                // Get transaction data
                const serviceType = document.getElementById('serviceType').value;
                const subtotal = parseFloat(document.getElementById('total').textContent.replace('₱',
                    ''));
                const discountAmount = parseFloat(document.getElementById('discount-amount').textContent
                    .replace('₱', ''));
                const discountPercentage = parseFloat(document.getElementById('discount-input').value ||
                    '0');
                const changeAmount = parseFloat(document.getElementById('change-amount').value) || 0;

                // Get advance payment amount if that payment type is selected
                let usedAdvancePayment = 0;
                if (paymentType === 'advance_payment') {
                    const advanceText = document.getElementById('advanceToUse').textContent;
                    usedAdvancePayment = parseFloat(advanceText.replace('₱', '').trim()) || 0;
                    if (usedAdvancePayment <= 0) {
                        throw new Error('Invalid advance payment amount');
                    }
                }

                // Get items from cart
                const items = Array.from(document.querySelector('.table-striped tbody').rows).map(row =>
                    ({
                        product_id: parseInt(row.dataset.productId),
                        kilos: parseFloat(row.querySelector('.number-spinner').value),
                        price_per_kilo: parseFloat(row.querySelector('.price-input').value),
                        total: parseFloat(row.querySelector('.subtotal').textContent.replace(
                            '₱', ''))
                    }));

                // Calculate remaining balance for balance update
                const remainingBalance = totalAmount - amountPaid;

                // Prepare transaction data
                const transactionData = {
                    customer_id: parseInt(customerId),
                    service_type: String(serviceType),
                    payment_type: String(paymentType),
                    reference_number: paymentType === 'online' ? String(document.getElementById(
                        'reference-number').value) : null,
                    used_advance_payment: paymentType === 'advance_payment' ? Number(
                        usedAdvancePayment.toFixed(2)) : null,
                    items: items,
                    subtotal: Number(subtotal.toFixed(2)),
                    discount_percentage: Number(discountPercentage.toFixed(2)),
                    discount_amount: Number(discountAmount.toFixed(2)),
                    total_amount: Number(totalAmount.toFixed(2)),
                    receipt_id: String(document.getElementById('receiptID').textContent),
                    status: serviceType === 'deliver' ? 'Not Assigned' : null,
                    amount_paid: Number(amountPaid.toFixed(2)),
                    change_amount: Number(changeAmount.toFixed(2)),
                    remaining_balance: Number(Math.max(0, remainingBalance).toFixed(
                        2)) // Add remaining balance
                };

                // Send transaction to server
                const response = await fetch('/save-transaction', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(transactionData)
                });

                const result = await response.json();
                if (!result.success) {
                    throw new Error(result.message || 'Transaction failed');
                }

                // Handle success
                let message = `Payment successful! Transaction ID: ${result.transaction_id}`;
                if (remainingBalance > 0) {
                    message +=
                    `\n₱${remainingBalance.toFixed(2)} has been added to customer's balance.`;
                }

                // Close modal and reset UI
                const modal = bootstrap.Modal.getInstance(document.getElementById('invoiceModal'));
                if (modal) {
                    modal.hide();
                }

                // Clear cart and reset UI elements
                document.querySelector('.table-striped tbody').innerHTML = '';
                ['total', 'discount-amount', 'total-after-discount'].forEach(id => {
                    document.getElementById(id).textContent = '₱0.00';
                });

                ['discount-input', 'amount-paid', 'change-amount', 'customer-search',
                    'selected-customer-id', 'sku-input'
                ]
                .forEach(id => {
                    document.getElementById(id).value = '';
                });

                await Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: message,
                    allowOutsideClick: false
                });

                // Final cleanup and refresh
                resetUIState();
                window.location.reload();

            } catch (error) {
                console.error('Payment error:', error);
                await Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: error.message || 'Failed to process payment'
                });
            } finally {
                isSubmitting = false;
                confirmButton.disabled = false;
            }
        };
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Calculate Change Function
        function calculateChange() {
            const amountPaid = parseFloat(document.getElementById('amount-paid').value) || 0;
            const grandTotal = parseFloat(document.querySelector('.receipt-total .fw-bold:last-child span')
                .textContent.replace(/[₱,\s]/g, '')) || 0;

            const change = amountPaid - grandTotal;
            const changeInput = document.getElementById('change-amount');
            changeInput.value = change >= 0 ? change.toFixed(2) : '0.00';
        }

        // Save Transaction Function
        async function saveTransaction() {
            try {
                const customerId = document.getElementById('selected-customer-id').value;
                if (!customerId) {
                    throw new Error('Customer ID is required');
                }

                const paymentType = document.getElementById('paymentType').value;
                const amountPaid = parseFloat(document.getElementById('amount-paid').value) || 0;
                const changeAmount = parseFloat(document.getElementById('change-amount').value) || 0;
                const subtotal = parseFloat(document.getElementById('total').textContent.replace('₱', ''));
                const discountAmount = parseFloat(document.getElementById('discount-amount').textContent
                    .replace('₱', ''));
                const totalAfterDiscount = parseFloat(document.getElementById('total-after-discount')
                    .textContent.replace('₱', ''));
                const discountPercentage = parseFloat(document.getElementById('discount-input').value ||
                    '0');
                const serviceType = document.getElementById('serviceType').value;

                // Get advance payment amount if that payment type is selected
                let usedAdvancePayment = 0;
                if (paymentType === 'advance_payment') {
                    const advanceText = document.getElementById('advanceToUse').textContent;
                    usedAdvancePayment = parseFloat(advanceText.replace('₱', '').trim()) || 0;
                    if (usedAdvancePayment <= 0) {
                        throw new Error('Invalid advance payment amount');
                    }
                }

                const items = Array.from(document.querySelector('.table-striped tbody').rows).map(row => ({
                    product_id: parseInt(row.dataset.productId),
                    kilos: parseFloat(row.querySelector('.number-spinner').value),
                    price_per_kilo: parseFloat(row.querySelector('.price-input').value),
                    total: parseFloat(row.querySelector('.subtotal').textContent.replace('₱',
                        ''))
                }));

                // Format numbers properly and ensure strings are properly passed
                const transactionData = {
                    customer_id: parseInt(customerId),
                    service_type: String(serviceType),
                    payment_type: String(paymentType), // Ensure it's passed as a string
                    reference_number: paymentType === 'online' ? String(document.getElementById(
                        'reference-number').value) : null,
                    used_advance_payment: paymentType === 'advance_payment' ? Number(usedAdvancePayment
                        .toFixed(2)) : null,
                    items: items,
                    subtotal: Number(subtotal.toFixed(2)),
                    discount_percentage: Number(discountPercentage.toFixed(2)),
                    discount_amount: Number(discountAmount.toFixed(2)),
                    total_amount: Number(totalAfterDiscount.toFixed(2)),
                    receipt_id: String(document.getElementById('receiptID').textContent),
                    status: serviceType === 'deliver' ? 'Not Assigned' : null,
                    amount_paid: Number(amountPaid.toFixed(2)),
                    change_amount: Number(changeAmount.toFixed(2))
                };

                const response = await fetch('/save-transaction', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(transactionData)
                });

                const result = await response.json();
                if (!result.success) {
                    throw new Error(result.message || 'Transaction failed');
                }

                return result;
            } catch (error) {
                console.error('Save Transaction Error:', error);
                throw error;
            }
        }

        // Event Listeners for Payment Handling
        document.getElementById('amount-paid').addEventListener('input', calculateChange);
        document.getElementById('discount-input').addEventListener('input', calculateChange);

        // Modal Payment State Handling
        document.getElementById('invoiceModal').addEventListener('shown.bs.modal', function() {
            const lastAmountPaid = localStorage.getItem('lastAmountPaid');
            if (lastAmountPaid) {
                document.getElementById('amount-paid').value = lastAmountPaid;
                calculateChange();
            }
        });

        document.getElementById('invoiceModal').addEventListener('hidden.bs.modal', function() {
            localStorage.removeItem('lastAmountPaid');
        });
    });
</script>
