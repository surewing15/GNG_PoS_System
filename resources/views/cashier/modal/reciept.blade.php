<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Receipt</title>
    <link rel="stylesheet" href="path/to/bootstrap.css"> <!-- Include Bootstrap CSS -->
    <style>
        .receipt-page {
            max-width: 600px;
            margin: auto;
            padding: 20px;
            border: 1px solid #ddd;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .no-print {
            display: none;
        }

        @media print {
            .no-print {
                display: none;
            }
        }
    </style>
</head>

<body>

    <div class="receipt-page">
        <div class="text-center">
            <h2>Receipt</h2>
            <div class="receipt-brand mb-2">
                <img src="./images/logo-dark.png" alt="Logo" style="max-width: 100px;"> <!-- Logo Image -->
            </div>
            <h4>Thank you for your purchase!</h4>
            <p class="small-text">Jhong Pax</p>
            <p class="small-text">Phone: +012 8764 556</p>
        </div>

        <div class="receipt-info text-start mb-3">
            <p><strong>Date:</strong> <span id="receipt-date">26 Jan, 2020</span></p>
            <p><strong>Receipt #:</strong> <span id="receipt-number">66K5W3</span></p>
        </div>

        <div class="receipt-items">
            <table class="table table-borderless">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th class="text-end">Qty</th>
                        <th class="text-end">Price</th>
                        <th class="text-end">Total</th>
                    </tr>
                </thead>
                <tbody id="receipt-items-body">
                    <!-- JavaScript will populate the receipt items here -->
                </tbody>
            </table>
        </div>

        <div class="receipt-total text-end mt-3">
            <p><strong>Total:</strong> <span id="receipt-grand-total">$478.50</span></p>
        </div>

        <p class="receipt-footer small-text mt-2 text-muted text-center">
            This receipt was generated electronically and is valid without a signature or seal.
        </p>

        <div class="text-center no-print mt-4">
            <button class="btn btn-secondary no-print" onclick="window.print()">Print</button>
            <button class="btn btn-primary no-print" onclick="downloadReceipt()">Download</button>
            <button class="btn btn-success no-print" onclick="confirmPayment()">Confirm Payment</button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>

    <script>
        function prepareReceipt() {
            let receiptContent = '';
            let grandTotal = 0;

            // Populate receipt items (for demonstration, replace with actual data)
            document.querySelectorAll('#cart-table-body tr').forEach(row => {
                const productName = row.querySelector('td:nth-child(1)').textContent;
                const quantity = row.querySelector('input[id^="quantity-"]').value;
                const price = row.querySelector('input[id^="price-"]').value;
                const total = (quantity * price).toFixed(2);

                grandTotal += parseFloat(total);

                receiptContent += `
                <tr>
                    <td>${productName}</td>
                    <td class="text-end">${quantity}</td>
                    <td class="text-end">$${parseFloat(price).toFixed(2)}</td>
                    <td class="text-end">$${total}</td>
                </tr>
            `;
            });

            document.getElementById('receipt-items-body').innerHTML = receiptContent;
            document.getElementById('receipt-grand-total').textContent = `$${grandTotal.toFixed(2)}`;
        }

        async function downloadReceipt() {
            const {
                jsPDF
            } = window.jspdf;
            const pdfDoc = new jsPDF();

            // Hide download/print buttons before converting to PDF
            document.querySelectorAll('.no-print').forEach(elem => elem.style.display = 'none');

            const receiptPage = document.querySelector('.receipt-page');

            // Convert receipt content to canvas, then to PDF
            const canvas = await html2canvas(receiptPage, {
                scale: 2
            });
            const imgData = canvas.toDataURL('image/png');

            const imgWidth = 190; // Fit width in PDF
            const imgHeight = (canvas.height * imgWidth) / canvas.width; // Maintain aspect ratio
            pdfDoc.addImage(imgData, 'PNG', 10, 10, imgWidth, imgHeight);

            pdfDoc.save('Receipt.pdf');

            // Restore download/print buttons after PDF generation
            document.querySelectorAll('.no-print').forEach(elem => elem.style.display = 'inline');
        }

        function confirmPayment() {
            alert('Payment confirmed!');
        }

        // Call this function to populate the receipt when the page loads
        document.addEventListener('DOMContentLoaded', prepareReceipt);

        function generateReceipt() {
            const tbody = document.querySelector('.table-striped tbody');
            const items = [];
            let total = 0;

            Array.from(tbody.querySelectorAll('tr')).forEach(row => {
                const sku = row.querySelector('th').textContent;
                const quantity = row.querySelector('.number-spinner').value;
                const price = row.querySelector('.price-input').value;
                const subtotal = parseFloat(row.querySelector('.subtotal').textContent);

                items.push({
                    product_sku: sku,
                    quantity: quantity,
                    price: price,
                    subtotal: subtotal
                });
                total += subtotal;
            });

            const discountPercent = document.getElementById('discount-input').value || 0;
            const discountAmount = (total * discountPercent) / 100;
            const finalAmount = total - discountAmount;

            // Populate receipt
            const receiptItemsBody = document.getElementById('receipt-items-body');
            receiptItemsBody.innerHTML = items.map(item => `
        <tr>
            <td>${item.product_sku}</td>
            <td class="text-end">${item.quantity}</td>
            <td class="text-end">₱${parseFloat(item.price).toFixed(2)}</td>
            <td class="text-end">₱${item.subtotal.toFixed(2)}</td>
        </tr>
    `).join('');

            document.getElementById('receipt-date').textContent = new Date().toLocaleDateString();
            document.getElementById('receipt-grand-total').textContent = `₱${finalAmount.toFixed(2)}`;

            // Show receipt modal
            const receiptModal = new bootstrap.Modal(document.getElementById('receiptModal'));
            receiptModal.show();
        }

        // Add this to your payment button click handler
        document.querySelector('button[data-bs-target="#invoiceModal"]').addEventListener('click', function() {
            generateReceipt();
        });
    </script>

</body>

</html>
