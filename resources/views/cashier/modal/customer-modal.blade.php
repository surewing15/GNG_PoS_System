<!-- Modal -->
<div class="modal fade" id="customerModal" tabindex="-1" role="dialog" aria-labelledby="customerModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-top" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Customer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('customers.save') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mt-2 align-items-center">
                        <div class="col-lg-5">
                            <label class="form-label" for="inp_fn">First Name <b class="text-danger">*</b></label>
                            <span class="form-note">Specify the Customer Name here.</span>
                        </div>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="inp_fn" name="inp_fn"
                                placeholder="Enter First Name.." required>
                        </div>
                    </div>

                    <div class="row mt-2 align-items-center">
                        <div class="col-lg-5">
                            <label class="form-label" for="inp_ls">Last Name <b class="text-danger">*</b></label>
                            <span class="form-note">Specify the Customer Name here.</span>
                        </div>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="inp_ls" name="inp_ls"
                                placeholder="Enter Last Name.." required>
                        </div>
                    </div>

                    <div class="row mt-2 align-items-center">
                        <div class="col-lg-5">
                            <label class="form-label" for="inp_address">Address <b class="text-danger">*</b></label>
                            <span class="form-note">Specify the Customer Address here.</span>
                        </div>
                        <div class="col-lg-7">
                            <input type="text" class="form-control" id="inp_address" name="inp_address"
                                placeholder="Enter Address.." required>
                        </div>
                    </div>

                    <div class="row mt-2 align-items-center">
                        <div class="col-lg-5">
                            <label class="form-label" for="inp_phone">Phone Number <b class="text-danger">*</b></label>
                            <span class="form-note">Specify the Phone Number here.</span>
                        </div>
                        <div class="col-lg-7">
                            <input type="number" class="form-control" id="inp_phone" name="inp_phone"
                                placeholder="Enter Phone Number.." required>
                        </div>
                    </div>

                    <div class="row mt-2 align-items-center">
                        <div class="col-lg-5">
                            <label class="form-label" for="inp_balance">Balance <b class="text-danger">*</b></label>
                            <span class="form-note">Specify the Customer Balance here.</span>
                        </div>
                        <div class="col-lg-7">
                            <input type="number" class="form-control" id="inp_balance" name="inp_balance"
                                placeholder="Enter Balance.." required>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-light bg-white">Reset</button>
                    <button type="submit" id="submitBtn" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none me-2" id="spinner"></span>
                        <span id="btnText">Submit Record</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    let isSubmitting = false;

    function handleSubmit(e) {
        if (isSubmitting) {
            e.preventDefault();
            return false;
        }

        isSubmitting = true;
        const btn = document.getElementById('submitBtn');
        const spinner = document.getElementById('spinner');
        const btnText = document.getElementById('btnText');

        btn.disabled = true;
        spinner.classList.remove('d-none');
        btnText.textContent = 'Processing...';

        return true;
    }

    // Reset form state if modal is closed
    document.getElementById('customerModal').addEventListener('hidden.bs.modal', () => {
        isSubmitting = false;
        const btn = document.getElementById('submitBtn');
        const spinner = document.getElementById('spinner');
        const btnText = document.getElementById('btnText');

        btn.disabled = false;
        spinner.classList.add('d-none');
        btnText.textContent = 'Submit Record';
    });
</script>
<!-- Include SweetAlert -->
@include('admin.sweet-alert.alert')
