<!-- Include html2pdf.js Library -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2pdf.js/0.9.2/html2pdf.bundle.min.js"></script>

<!-- Modal Structure with Receipt Style, Print, Download, and Confirm Payment Options -->
<div class="modal fade" id="truckModal" tabindex="-1" aria-labelledby="truckModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-receipt-width">
        <div class="modal-content receipt-style" id="receiptContent">
            <div class="modal-header">
                <h5 class="modal-title" id="truckModalLabel">Trucking Order </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body receipt-print-content">
                <div class="receipt-info text-start mb-3">
                    <div class="row">
                        <div class="col-6"><strong>Date:</strong></div>
                        <div class="col-6">26 Jan, 2020</div>

                        <div class="col-6"><strong>Trucking Order No #:</strong></div>
                        <div class="col-6">66K5W3</div>

                        <div class="col-6"><strong>Pos Receipt #:</strong></div>
                        <div class="col-6">66K5W3</div>
                    </div>
                </div>

                <div class="receipt-details">
                    <div class="row">
                        <div class="col-6"><strong>Driver:</strong></div>
                        <div class="col-6">Kanor</div>

                        <div class="col-6"><strong>Helper:</strong></div>
                        <div class="col-6">Thomas</div>

                        <div class="col-6"><strong>Customer's Name:</strong></div>
                        <div class="col-6">Nestor</div>

                        <div class="col-6"><strong>Destination:</strong></div>
                        <div class="col-6">tagoloan, casinglot</div>

                        <div class="col-6"><strong>Expenses:</strong></div>
                        <div class="col-6">₱ 100.00</div>

                        <div class="col-6"><strong>Allowance:</strong></div>
                        <div class="col-6">₱ 100.00</div>

                        <div class="col-6"><strong>Total Kilo:</strong></div>
                        <div class="col-6">45 kg</div>
                    </div>
                </div>

            </div>
            <div class="modal-footer no-print">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
               
                <button type="button" class="btn btn-primary" onclick="printReceipt()">Print</button>
                <button type="button" class="btn btn-info" onclick="downloadPDF()">Download PDF</button>
            </div>
        </div>
    </div>
</div>

<script>
    function prepareReceipt() {
        let receiptContent = '';

        $('#cart-table-body tr').each(function() {
            const productName = $(this).find('td:nth-child(1)').text();
            const quantity = $(this).find('input[id^="quantity-"]').val();
            const price = $(this).find('input[id^="price-"]').val();
            const total = (quantity * price).toFixed(2);

            receiptContent += `
                <tr>
                    <td>${productName}</td>
                    <td class="text-end">${quantity}</td>
                    <td class="text-end">$${parseFloat(price).toFixed(2)}</td>
                </tr>
            `;
        });

        $('.receipt-items tbody').html(receiptContent);

        $('#truckModal').modal('show');
    }

    function printReceipt() {
        const receiptContent = document.getElementById('receiptContent').cloneNode(true);
        const printWindow = window.open('', '_blank');

        const modalFooter = receiptContent.querySelector('.modal-footer');
        if (modalFooter) {
            modalFooter.remove();
        }

        printWindow.document.write(`
            <html>
                <head>
                    <title>Print Receipt</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
                        .receipt-style { width: 100%; margin: 0 auto; padding: 20px; }
                        .receipt-header, .receipt-total { text-align: center; }
                        .receipt-items, .receipt-items th, .receipt-items td {
                            border-collapse: collapse;
                            width: 100%;
                            border: none;
                            padding: 8px;
                        }
                        .receipt-items th { text-align: left; }
                        .receipt-footer { text-align: center; font-size: 0.9em; color: #555; margin-top: 20px; }
                        img { max-width: 100px; }
                    </style>
                </head>
                <body>
                    <div class="receipt-style">${receiptContent.innerHTML}</div>
                </body>
            </html>
        `);

        printWindow.document.close();

        setTimeout(() => {
            printWindow.print();
            printWindow.close();
        }, 500);
    }

    function downloadPDF() {
        const receiptContent = document.getElementById('receiptContent').cloneNode(true);

        const modalFooter = receiptContent.querySelector('.modal-footer');
        if (modalFooter) {
            modalFooter.remove();
        }

        const options = {
            margin:       0.5,
            filename:     'receipt.pdf',
            image:        { type: 'jpeg', quality: 0.98 },
            html2canvas:  { scale: 2 },
            jsPDF:        { unit: 'in', format: 'a4', orientation: 'portrait' }
        };

        html2pdf().set(options).from(receiptContent).save();
    }
</script>
