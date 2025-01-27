<!-- Modal Structure with Custom Width -->
<div class="modal fade" id="invoiceModal" tabindex="-1" aria-labelledby="invoiceModalLabel" aria-hidden="true">
    <div class="modal-dialog custom-modal-width">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="invoiceModalLabel">Invoice</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="nk-block">
                    <div class="invoice">
                        <div class="invoice-action text-end mb-3">
                            <a class="btn btn-icon btn-lg btn-white btn-dim btn-outline-primary" href="html/invoice-print.html" target="_blank">
                                <em class="icon ni ni-printer-fill"></em>
                            </a>
                        </div>
                        <div class="invoice-wrap">
                            <div class="invoice-brand text-center mb-4">
                                <img src="./images/logo-dark.png" srcset="./images/logo-dark2x.png 2x" alt="Logo">
                            </div>
                            <div class="invoice-head d-flex justify-content-between mb-4">
                                <div class="invoice-contact">
                                    <span class="overline-title">Invoice To</span>
                                    <div class="invoice-contact-info">
                                        <h4 class="title">Gregory Anderson</h4>
                                        <ul class="list-plain">
                                            <li><em class="icon ni ni-map-pin-fill"></em><span>House #65, 4328 Marion Street<br>Newbury, VT 05051</span></li>
                                            <li><em class="icon ni ni-call-fill"></em><span>+012 8764 556</span></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="invoice-desc">
                                    <h3 class="title">Invoice</h3>
                                    <ul class="list-plain">
                                        <li class="invoice-id"><span>Invoice ID</span>: <span>66K5W3</span></li>
                                        <li class="invoice-date"><span>Date</span>: <span>26 Jan, 2020</span></li>
                                    </ul>
                                </div>
                            </div>
                            <div class="invoice-bills">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th class="w-150px">Item Code (SKU)</th>
                                                <th class="w-100px">Price</th>
                                                <th class="w-100px">Quantity</th>
                                                <th class="w-100px">Amount</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <tr>
                                                <td>P1</td>
                                                <td>$40.00</td>
                                                <td>5</td>
                                                <td>$200.00</td>
                                            </tr>
                                            <tr>
                                                <td>p2</td>
                                                <td>$25.00</td>
                                                <td>1</td>
                                                <td>$25.00</td>
                                            </tr>
                                            <tr>
                                                <td>p3</td>
                                                <td>$131.25</td>
                                                <td>1</td>
                                                <td>$131.25</td>
                                            </tr>
                                            <tr>
                                                <td>23604094</td>
                                                <td>$78.75</td>
                                                <td>1</td>
                                                <td>$78.75</td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="3" class="text-end">Total</td>
                                                <td>$478.50</td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                    <div class="nk-notes ff-italic fs-12px text-soft">
                                        Invoice was created on a computer and is valid without the signature and seal.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="confirmButton">Confirm</button>
            </div>
        </div>
    </div>
</div>

<!-- CSS to adjust the width -->
<style>
    /* Set custom width for the modal dialog */
    .custom-modal-width {
        max-width: 80%; /* Adjust percentage or use px as desired */
    }
    /* Adjust column widths */
    .w-150px {
        width: 150px;
    }
    .w-100px {
        width: 100px;
    }
</style>
