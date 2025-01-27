<div class="modal fade" id="discountModal" tabindex="-1" role="dialog" aria-labelledby="discountModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Authorization Required</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form id="discount-form">
                <div class="modal-body">
                    <!-- Password Input -->
                    <div class="row mt-2 align-items-center">
                        <div class="col-lg-5">
                            <label class="form-label" for="discount-password">Authorization Code</label>
                            <span class="form-note">Enter password to apply discount</span>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-control-wrap">
                                <div class="form-icon form-icon-right">
                                    <em class="icon ni ni-lock-alt"></em>
                                </div>
                                <input type="password" class="form-control" id="discount-password" required
                                    placeholder="Enter authorization code">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light bg-white" onclick="resetDiscount()">
                        <em class="icon ni ni-repeat"></em> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <em class="icon ni ni-check"></em> Apply Discount
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    // Add event listener for when the discount modal is shown
    document.addEventListener('DOMContentLoaded', function() {
        const discountModal = document.getElementById('discountModal');
        const passwordInput = document.getElementById('discount-password');
        const discountForm = document.getElementById('discount-form');

        // Focus on password input when modal opens
        discountModal.addEventListener('shown.bs.modal', function() {
            passwordInput.focus();
        });

        // Clear password when modal is hidden
        discountModal.addEventListener('hidden.bs.modal', function() {
            passwordInput.value = '';
        });
    });
</script>
