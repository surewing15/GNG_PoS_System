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
                                    <th class="text-end">Head</th>
                                    {{-- <th class="text-end">DR</th> --}}
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
        // Update the updateReceiptItems function to remove DR from display
        function updateReceiptItems() {
            const receiptTbody = document.querySelector('#invoiceModal .receipt-items tbody');
            const cartItems = document.querySelectorAll('.table-striped tbody tr');

            receiptTbody.innerHTML = '';
            const aggregatedItems = {};

            cartItems.forEach((item) => {
                try {
                    const sku = item.querySelector('th')?.textContent?.trim();
                    const kilos = parseFloat(item.querySelector('.number-spinner')?.value) || 0;
                    const head = parseInt(item.querySelector('.head-input')?.value) || 0;
                    const dr = item.dataset.dr || ''; // Still get DR for data but don't display it
                    const price = parseFloat(item.querySelector('.price-input')?.value) || 0;
                    const total = parseFloat(item.querySelector('.subtotal')?.textContent?.replace(
                        /[₱,\s]/g, '')) || 0;

                    if (sku && kilos > 0 && price > 0 && total > 0) {
                        if (aggregatedItems[sku]) {
                            aggregatedItems[sku].kilos += kilos;
                            aggregatedItems[sku].head += head;
                            aggregatedItems[sku].dr = dr; // Keep DR in data
                            aggregatedItems[sku].total += total;
                        } else {
                            aggregatedItems[sku] = {
                                kilos,
                                head,
                                dr, // Keep DR in data
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
                head,
                price,
                total
            }]) => {
                const row = document.createElement('tr');
                row.innerHTML = `
            <td class="text-start">${sku}</td>
            <td class="text-end">${kilos.toFixed(2)}</td>
            <td class="text-end">${head}</td>
            <td class="text-end">₱${price.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
            <td class="text-end">₱${total.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</td>
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

                // Calculate totals directly from receipt items
                const receiptItems = document.querySelectorAll('.receipt-items tbody tr');
                let subtotalValue = 0;

                receiptItems.forEach(row => {
                    const totalText = row.cells[4].textContent;
                    const total = parseFloat(totalText.replace(/[₱,\s]/g, '')) || 0;
                    subtotalValue += total;
                });

                // Get discount from the original input
                const discountPercentage = parseFloat(document.getElementById('discount-input').value) || 0;
                const discountValue = (subtotalValue * discountPercentage) / 100;
                const grandTotal = subtotalValue - discountValue;

                const receiptTotal = document.querySelector('.receipt-total');
                if (receiptTotal) {
                    receiptTotal.innerHTML = `
                <div class="d-flex justify-content-between mb-2">
                    <strong>Subtotal:</strong>
                    <span>₱${subtotalValue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <strong>Discount:</strong>
                    <span>₱${discountValue.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                </div>
                <div class="d-flex justify-content-between fw-bold">
                    <strong>GRAND TOTAL:</strong>
                    <span>₱${grandTotal.toLocaleString('en-US', {minimumFractionDigits: 2, maximumFractionDigits: 2})}</span>
                </div>
            `;
                }

                const amountPaidInput = document.getElementById('amount-paid');
                if (amountPaidInput.value) {
                    calculateChange();
                }
            } catch (error) {
                console.error('Error updating modal content:', error);
                console.error('Error details:', error.stack);
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
                    items: Array.from(document.querySelectorAll('.receipt-items tbody tr'))
                        .map(
                            row => ({
                                sku: row.cells[0].textContent,
                                kilos: parseFloat(row.cells[1].textContent),
                                head: parseInt(row.cells[2].textContent),
                                price_per_kilo: parseFloat(row.cells[3].textContent
                                    .replace(
                                        '₱', '')),
                                total: parseFloat(row.cells[4].textContent.replace('₱',
                                    ''))
                            })),
                    subtotal: parseFloat(document.querySelector(
                            '.receipt-total .mb-2:first-child span').textContent
                        .replace(
                            '₱', '')),
                    discount_amount: parseFloat(document.querySelector(
                            '.receipt-total .mb-2:nth-child(2) span').textContent
                        .replace(
                            '₱', '')),
                    total_amount: parseFloat(document.querySelector(
                        '.receipt-total .fw-bold span').textContent.replace('₱',
                        '')),
                    amount_paid: document.getElementById('amount-paid').value ? parseFloat(
                        document.getElementById('amount-paid').value) : null,
                    change_amount: document.getElementById('change-amount').value ?
                        parseFloat(
                            document.getElementById('change-amount').value) : null,
                    used_advance_payment: document.getElementById('advanceToUse') ?
                        parseFloat(
                            document.getElementById('advanceToUse').textContent.replace('₱',
                                '')
                        ) : null,
                    reference_number: document.getElementById('reference-number') ? document
                        .getElementById('reference-number').value : null
                };

                const response = await fetch('/cashier/print-receipt', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector(
                                'meta[name="csrf-token"]')
                            .content
                    },
                    body: JSON.stringify(receiptData)
                });

                if (!response.ok) throw new Error('Print failed');

                const result = await response.json();
                if (!result.success) throw new Error(result.message);

                Swal.fire({
                    icon: 'success',
                    title: 'Receipt printed successfully'
                });

            } catch (error) {
                console.error('Print error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Print Error',
                    text: error.message
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
            const cleanAvailableAdvance = parseFloat(String(availableAdvance).replace(/[₱,\s]/g, ''));
            const cleanGrandTotal = parseFloat(String(grandTotal).replace(/[₱,\s]/g, ''));
            const amountToUse = Math.min(cleanAvailableAdvance, cleanGrandTotal);

            availableAdvanceElement.textContent = `₱${cleanAvailableAdvance.toFixed(2)}`;
            advanceToUseElement.textContent = `₱${amountToUse.toFixed(2)}`;

            if (cleanAvailableAdvance < cleanGrandTotal) {
                const remainingAmount = (cleanGrandTotal - cleanAvailableAdvance).toFixed(2);
                insufficientAdvanceWarning.style.display = 'block';
                insufficientAdvanceWarning.innerHTML = `
            <div class="alert alert-warning">
                <p>Insufficient advance payment. Additional payment of ₱${remainingAmount} required.</p>
                <div class="mt-2">
                    <button class="btn btn-primary btn-sm me-2" onclick="handleAdditionalPayment('cash')">
                        Pay with Cash
                    </button>
                    <button class="btn btn-secondary btn-sm" onclick="handleAdditionalPayment('credit')">
                        Add to Balance
                    </button>
                </div>
            </div>`;
            } else {
                insufficientAdvanceWarning.style.display = 'none';
            }

            document.getElementById('amount-paid').value = amountToUse.toFixed(2);
            document.getElementById('change-amount').value = '0.00';
        }
        window.handleAdditionalPayment = async function(type) {
            const grandTotal = parseFloat(document.querySelector('.receipt-total .fw-bold span')
                .textContent.replace(/[₱,\s]/g, ''));
            const availableAdvance = parseFloat(document.getElementById('availableAdvance').textContent
                .replace(/[₱,\s]/g, ''));
            const remainingAmount = grandTotal - availableAdvance;

            if (type === 'cash') {
                // Show cash payment input
                const {
                    value: cashAmount
                } = await Swal.fire({
                    title: 'Enter Cash Amount',
                    input: 'number',
                    inputLabel: `Additional Payment Required: ₱${remainingAmount.toFixed(2)}`,
                    inputValue: remainingAmount.toFixed(2),
                    showCancelButton: true,
                    inputValidator: (value) => {
                        if (!value || parseFloat(value) < remainingAmount) {
                            return 'Please enter sufficient amount!';
                        }
                    }
                });

                if (cashAmount) {
                    // Update payment amounts
                    const totalPaid = availableAdvance + parseFloat(cashAmount);
                    document.getElementById('amount-paid').value = totalPaid.toFixed(2);
                    const change = totalPaid - grandTotal;
                    document.getElementById('change-amount').value = change.toFixed(2);

                    // Update warning message
                    insufficientAdvanceWarning.innerHTML = `
                <div class="alert alert-success">
                    <p>Payment completed with:</p>
                    <p>Advance Payment: ₱${availableAdvance.toFixed(2)}</p>
                    <p>Cash Payment: ₱${cashAmount}</p>
                    <p>Change: ₱${change.toFixed(2)}</p>
                </div>`;
                }
            } else if (type === 'credit') {
                // Show confirmation for adding to balance
                const {
                    isConfirmed
                } = await Swal.fire({
                    title: 'Confirm Balance Addition',
                    html: `
                <p>The remaining amount of ₱${remainingAmount.toFixed(2)} will be added to the customer's balance.</p>
                <p>Advance Payment Used: ₱${availableAdvance.toFixed(2)}</p>
                <p>Amount to Credit: ₱${remainingAmount.toFixed(2)}</p>
            `,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Yes, add to balance',
                    cancelButtonText: 'Cancel'
                });

                if (isConfirmed) {
                    // Update payment display - Set to 0 instead of availableAdvance
                    document.getElementById('amount-paid').value = '0.00';
                    document.getElementById('change-amount').value = '0.00';

                    // Update warning message to reflect the credit arrangement
                    insufficientAdvanceWarning.innerHTML = `
                <div class="alert alert-info">
                    <p>Payment split:</p>
                    <p>Advance Payment Used: ₱${availableAdvance.toFixed(2)}</p>
                    <p>Added to Balance: ₱${remainingAmount.toFixed(2)}</p>
                    <p class="text-info">Amount Paid: ₱0.00 (Full amount added to balance)</p>
                </div>`;
                }
            }
        };

        async function handlePaymentTypeChange() {
            const selectedValue = paymentTypeSelect.value;
            const customerId = document.getElementById('selected-customer-id').value;

            // Clean up the grand total parsing
            const grandTotalElement = document.querySelector('.receipt-total .fw-bold span');
            const grandTotal = parseFloat(grandTotalElement.textContent.replace(/[₱,\s]/g, '')) || 0;

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

                // Add debugging logs
                console.log('Available Advance:', availableAdvance);
                console.log('Grand Total:', grandTotal);

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
        window.handleInsufficientAdvancePayment = async function(totalAmount, availableAdvance) {
            const remainingAmount = totalAmount - availableAdvance;

            const {
                value: choice,
                isConfirmed
            } = await Swal.fire({
                title: 'Insufficient Advance Payment',
                html: `
            <div class="text-left">
                <p>Total Amount: ₱${totalAmount.toFixed(2)}</p>
                <p>Advance Payment Available: ₱${availableAdvance.toFixed(2)}</p>
                <p class="font-weight-bold">Remaining Amount: ₱${remainingAmount.toFixed(2)}</p>
            </div>
        `,
                input: 'radio',
                inputOptions: {
                    'cash': 'Pay remaining with cash',
                    'credit': 'Add to customer balance'
                },
                inputValidator: (value) => {
                    if (!value) return 'Please choose an option!';
                },
                showCancelButton: true,
                confirmButtonText: 'Continue',
                cancelButtonText: 'Cancel'
            });

            if (!isConfirmed) return null;

            if (choice === 'credit') {
                // For credit option:
                // - used_advance_payment stores the advance payment used
                // - credit_charge stores the remaining amount
                // - amount_paid will be 0 since no additional cash payment
                return {
                    type: 'credit',
                    usedAdvancePayment: availableAdvance,
                    amountPaid: 0, // No cash payment
                    creditCharge: remainingAmount,
                    changeAmount: 0
                };
            } else {
                // For cash option, prompt for cash amount
                const {
                    value: cashAmount,
                    isConfirmed: cashConfirmed
                } = await Swal.fire({
                    title: 'Enter Cash Amount',
                    input: 'number',
                    inputLabel: `Amount Required: ₱${remainingAmount.toFixed(2)}`,
                    inputValue: remainingAmount.toFixed(2),
                    showCancelButton: true,
                    inputValidator: (value) => {
                        if (!value || parseFloat(value) < remainingAmount) {
                            return 'Please enter sufficient amount!';
                        }
                    }
                });

                if (!cashConfirmed) return null;

                const parsedCashAmount = parseFloat(cashAmount);
                const change = parsedCashAmount - remainingAmount;

                return {
                    type: 'cash',
                    usedAdvancePayment: availableAdvance,
                    amountPaid: parsedCashAmount, // Store remaining cash amount here
                    creditCharge: 0,
                    changeAmount: change
                };
            }
        };


        // Inside the confirmPayment function
        window.confirmPayment = async function() {
            // Get the confirm button early
            const confirmButton = document.querySelector('button.btn-success');

            // If already submitting, exit early
            if (isSubmitting) return;

            try {
                const paymentType = document.getElementById('paymentType').value;
                const grandTotal = parseFloat(document.querySelector('.receipt-total .fw-bold span')
                    .textContent.replace(/[₱,\s]/g, '')) || 0;
                const subtotal = parseFloat(document.querySelector(
                        '.receipt-total .mb-2:first-child span')
                    .textContent.replace(/[₱,\s]/g, '')) || 0;
                const discountAmount = parseFloat(document.querySelector(
                        '.receipt-total .mb-2:nth-child(2) span')
                    .textContent.replace(/[₱,\s]/g, '')) || 0;
                const discountPercentage = parseFloat(document.getElementById('discount-input').value ||
                    '0');
                const customerId = document.getElementById('selected-customer-id').value;

                // Validate reference number for online payment before setting isSubmitting
                if (paymentType === 'online') {
                    const referenceNumber = document.getElementById('reference-number').value;
                    if (!referenceNumber) {
                        await Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Reference number is required for online payment'
                        });
                        // Focus the reference number input
                        document.getElementById('reference-number').focus();
                        return;
                    }
                }

                // Validate customer selection before setting isSubmitting
                if (!customerId) {
                    await Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Please select a customer'
                    });
                    return;
                }

                // Only set submission state after initial validations pass
                isSubmitting = true;
                confirmButton.disabled = true;

                // Rest of your existing code for building transactionData...
                let transactionData = {
                    customer_id: parseInt(customerId),
                    service_type: document.getElementById('serviceType').value,
                    payment_type: paymentType,
                    items: Array.from(document.querySelector('.table-striped tbody').rows).map(
                        row => ({
                            product_id: parseInt(row.dataset.productId),
                            kilos: parseFloat(row.querySelector('.number-spinner').value),
                            head: parseInt(row.querySelector('.head-input').value) || 0,
                            dr: row.dataset.dr || '',
                            price_per_kilo: parseFloat(row.querySelector('.price-input')
                                .value),
                            total: parseFloat(row.querySelector('.number-spinner').value) *
                                parseFloat(row.querySelector('.price-input').value)
                        })),
                    subtotal: subtotal,
                    discount_percentage: discountPercentage,
                    discount_amount: discountAmount,
                    total_amount: grandTotal,
                    receipt_id: document.getElementById('receiptID').textContent,
                    status: document.getElementById('serviceType').value === 'deliver' ?
                        'Not Assigned' : null
                };

                // Handle different payment types
                if (paymentType === 'online') {
                    const referenceNumber = document.getElementById('reference-number').value;
                    if (!referenceNumber) {
                        throw new Error('Reference number is required for online payment');
                    }
                    transactionData = {
                        ...transactionData,
                        reference_number: referenceNumber,
                        amount_paid: grandTotal, // Full amount for online payment
                        credit_charge: 0, // No credit charge for online payment
                        change_amount: 0
                    };
                } else if (paymentType === 'advance_payment') {
                    const availableAdvance = parseFloat(document.getElementById('availableAdvance')
                        .textContent.replace(/[₱,\s]/g, '')) || 0;
                    const advancePaymentUsed = Math.min(availableAdvance, grandTotal);
                    const remainingAmount = grandTotal - advancePaymentUsed;

                    if (remainingAmount > 0) {
                        const {
                            isConfirmed,
                            value: paymentChoice
                        } = await Swal.fire({
                            title: 'Insufficient Advance Payment',
                            html: `
                <div class="text-left">
                    <p>Total Amount: ₱${grandTotal.toFixed(2)}</p>
                    <p>Advance Payment Used: ₱${advancePaymentUsed.toFixed(2)}</p>
                    <p class="font-weight-bold">Remaining Amount: ₱${remainingAmount.toFixed(2)}</p>
                </div>`,
                            input: 'radio',
                            inputOptions: {
                                'cash': 'Pay remaining with cash',
                                'credit': 'Add to customer balance'
                            },
                            inputValidator: (value) => {
                                if (!value) return 'Please choose an option!';
                            },
                            showCancelButton: true,
                            confirmButtonText: 'Continue',
                            cancelButtonText: 'Cancel'
                        });

                        if (!isConfirmed) {
                            isSubmitting = false;
                            confirmButton.disabled = false;
                            return;
                        }

                        if (paymentChoice === 'cash') {
                            const {
                                value: cashAmount,
                                isConfirmed: cashConfirmed
                            } = await Swal.fire({
                                title: 'Enter Cash Amount',
                                input: 'number',
                                inputLabel: `Amount Required: ₱${remainingAmount.toFixed(2)}`,
                                inputValue: remainingAmount.toFixed(2),
                                showCancelButton: true,
                                inputValidator: (value) => {
                                    if (!value || parseFloat(value) < remainingAmount) {
                                        return 'Please enter sufficient amount!';
                                    }
                                }
                            });

                            if (!cashConfirmed) {
                                isSubmitting = false;
                                confirmButton.disabled = false;
                                return;
                            }

                            transactionData = {
                                ...transactionData,
                                advance_payment_used: advancePaymentUsed,
                                amount_paid: parseFloat(cashAmount),
                                credit_charge: 0,
                                change_amount: parseFloat(cashAmount) - remainingAmount
                            };
                        } else {
                            // Add to customer balance option
                            transactionData = {
                                ...transactionData,
                                advance_payment_used: advancePaymentUsed, // Store advance payment used
                                amount_paid: 0, // No cash payment
                                credit_charge: remainingAmount, // Store remaining as credit
                                change_amount: 0
                            };
                        }
                    } else {
                        // Advance payment covers entire amount
                        transactionData = {
                            ...transactionData,
                            advance_payment_used: advancePaymentUsed,
                            amount_paid: 0,
                            credit_charge: 0,
                            change_amount: 0
                        };
                    }
                } else {
                    // Regular payment types (cash, debit)
                    const amountPaid = parseFloat(document.getElementById('amount-paid').value) || 0;
                    transactionData = {
                        ...transactionData,
                        amount_paid: amountPaid,
                        credit_charge: Math.max(0, grandTotal - amountPaid),
                        change_amount: Math.max(0, amountPaid - grandTotal)
                    };
                }

                // Show confirmation for credit charges if any
                if (transactionData.credit_charge > 0) {
                    const confirmCredit = await Swal.fire({
                        title: 'Credit Charge Confirmation',
                        html: `
                <div class="text-left">
                    <p>Total Amount: ₱${grandTotal.toFixed(2)}</p>
                    ${transactionData.advance_payment_used > 0 ?
                        `<p>Advance Payment Used: ₱${transactionData.advance_payment_used.toFixed(2)}</p>` : ''}
                    ${transactionData.amount_paid > 0 ?
                        `<p>Amount Paid: ₱${transactionData.amount_paid.toFixed(2)}</p>` : ''}
                    <p class="font-weight-bold text-danger">Credit Charge: ₱${transactionData.credit_charge.toFixed(2)}</p>
                </div>
                <p class="mt-3">This amount will be added as credit charge to the customer's balance. Continue?</p>`,
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Yes, continue',
                        cancelButtonText: 'No, cancel'
                    });

                    if (!confirmCredit.isConfirmed) {
                        isSubmitting = false;
                        confirmButton.disabled = false;
                        return;
                    }
                }

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

                // Create success message with payment breakdown
                let message = `Payment successful! Transaction ID: ${result.transaction_id}<br><br>`;
                message += '<div class="text-left">';
                if (transactionData.advance_payment_used > 0) {
                    message +=
                        `<p>Advance Payment: ₱${transactionData.advance_payment_used.toFixed(2)}</p>`;
                }
                if (transactionData.amount_paid > 0) {
                    message += `<p>Amount Paid: ₱${transactionData.amount_paid.toFixed(2)}</p>`;
                }
                if (transactionData.credit_charge > 0) {
                    message +=
                        `<p class="text-danger">Added to Balance: ₱${transactionData.credit_charge.toFixed(2)}</p>`;
                }
                if (transactionData.change_amount > 0) {
                    message += `<p>Change: ₱${transactionData.change_amount.toFixed(2)}</p>`;
                }
                message += '</div>';

                // Close modal and show success message
                const modal = bootstrap.Modal.getInstance(document.getElementById('invoiceModal'));
                if (modal) {
                    modal.hide();
                }

                await Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    html: message,
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

                // Initialize payment variables
                let usedAdvancePayment = 0;
                let creditCharge = 0;
                let finalAmountPaid = amountPaid;

                // Handle advance payment logic
                if (paymentType === 'advance_payment') {
                    const advanceText = document.getElementById('advanceToUse').textContent;
                    usedAdvancePayment = parseFloat(advanceText.replace(/[₱,\s]/g, '').trim()) || 0;

                    if (usedAdvancePayment <= 0) {
                        throw new Error('Invalid advance payment amount');
                    }

                    // Calculate if there's any remaining amount to be paid
                    const remainingAmount = totalAfterDiscount - usedAdvancePayment;

                    if (remainingAmount > 0) {
                        // If there's additional cash payment
                        if (amountPaid > 0) {
                            finalAmountPaid = amountPaid;
                            creditCharge = Math.max(0, remainingAmount - amountPaid);
                        } else {
                            // If no additional payment, treat as credit
                            creditCharge = remainingAmount;
                            finalAmountPaid = 0;
                        }
                    } else {
                        // Advance payment covers everything
                        finalAmountPaid = 0;
                        creditCharge = 0;
                    }
                } else {
                    // For non-advance payments, calculate credit charge normally
                    creditCharge = Math.max(0, totalAfterDiscount - amountPaid);
                }

                const items = Array.from(document.querySelector('.table-striped tbody').rows).map(row => {
                    const kilos = parseFloat(row.querySelector('.number-spinner').value);
                    const price_per_kilo = parseFloat(row.querySelector('.price-input').value);
                    return {
                        product_id: parseInt(row.dataset.productId),
                        kilos: kilos,
                        head: parseInt(row.querySelector('.head-input').value) || 0,
                        dr: row.dataset.dr || '',
                        price_per_kilo: price_per_kilo,
                        total: kilos * price_per_kilo
                    };
                });

                // Construct transaction data
                const transactionData = {
                    customer_id: parseInt(customerId),
                    service_type: String(serviceType),
                    payment_type: String(paymentType),
                    reference_number: paymentType === 'online' ? String(document.getElementById(
                        'reference-number').value) : null,
                    advance_payment_used: paymentType === 'advancePaymentUsed' ? Number(
                        usedAdvancePayment
                        .toFixed(2)) : null,
                    items: items,
                    subtotal: Number(subtotal.toFixed(2)),
                    discount_percentage: Number(discountPercentage.toFixed(2)),
                    discount_amount: Number(discountAmount.toFixed(2)),
                    total_amount: Number(totalAfterDiscount.toFixed(2)),
                    amount_paid: Number(finalAmountPaid.toFixed(2)),
                    credit_charge: Number(creditCharge.toFixed(2)),
                    change_amount: Number(changeAmount.toFixed(2)),
                    receipt_id: String(document.getElementById('receiptID').textContent),
                    status: serviceType === 'deliver' ? 'Not Assigned' : null
                };

                console.log('Sending transaction data:', transactionData);

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
