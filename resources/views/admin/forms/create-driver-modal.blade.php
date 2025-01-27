<!-- Modal -->
<div class="modal fade" id="driverModal" tabindex="-1" role="dialog" aria-labelledby="driverModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">New Driver</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form action="{{ route('driver.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="row mt-2 align-items-center">
                        <div class="col-lg-5">
                            <label class="form-label" for="inp_fn">First Name <b class="text-danger">*</b></label>
                            <span class="form-note">Specify the First Name here.</span>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="inp_fn" name="inp_fn"
                                    placeholder="Enter First Name.." required>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2 align-items-center">
                        <div class="col-lg-5">
                            <label class="form-label" for="inp_ln">Last Name <b class="text-danger">*</b></label>
                            <span class="form-note">Specify the Last Name here.</span>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="inp_ln" name="inp_ln"
                                    placeholder="Enter Last Name.." required>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2 align-items-center">
                        <div class="col-lg-5">
                            <label class="form-label" for="inp_phone">Phone <b class="text-danger">*</b></label>
                            <span class="form-note">Specify the Phone here.</span>
                        </div>
                        <div class="col-lg-7">
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" id="inp_phone" name="inp_phone"
                                    placeholder="Enter Phone Number.." required>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="reset" class="btn btn-light bg-white">
                        <em class="icon ni ni-repeat"></em> Reset
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <em class="icon ni ni-save"></em> Submit Record
                    </button>
                </div>
            </form>



        </div>
    </div>
</div>
