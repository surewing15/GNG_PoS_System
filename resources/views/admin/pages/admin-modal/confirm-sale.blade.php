<!-- Confirm Sale Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">
                    <i class="bi bi-cart-check text-primary"></i> Confirm Sale
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="" action="/customer/invoice" method="">
                <input type="hidden" name="_token" value="BHzI39LEmvwmJQi2cY8337ej4DbrKAgzrs0tJb4d" autocomplete="off">
                <div class="modal-body">
                    <div class="row">
                        <!-- Left column -->
                        <div class="col-lg-7">
                            <input type="hidden" value="1" name="customer_id">
                            <input type="hidden" value="0" name="tax_percentage">
                            <input type="hidden" value="0" name="discount_percentage">
                            <input type="hidden" value="0" name="shipping_amount">

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group">
                                        <label for="total_amount">Total Kilo <span class="text-danger">*</span></label>
                                        <input id="total_amount" type="text" class="form-control" name="total_amount" value="10kg" readonly required>
                                    </div>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="payment_method">Payment Method <span class="text-danger">*</span></label>
                                <select class="form-select" name="payment_method" id="payment_method" required>
                                    <option value="Cash">Cash</option>
                                    <option value="Credit Card">Credit Card</option>
                                    <option value="Bank Transfer">Bank Transfer</option>
                                    <option value="Cheque">Cheque</option>
                                    <option value="Other">Other</option>
                                </select>
                            </div>


                        </div>

                        <!-- Right column (Summary Table) -->
                        <div class="col-lg-5">
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <tbody>
                                        <tr>
                                            <th>Total Products</th>
                                            <td>
                                                <span class="badge bg-success">1</span>
                                            </td>
                                        </tr>

                                        <tr class="text-primary">
                                            <th>Grand Total</th>
                                            <th>(=) $121.21</th>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>
