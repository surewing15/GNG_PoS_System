@extends('admin.theme.layout')
@section('content')
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Edit Customer</h3>
                            </div>
                        </div>
                    </div>
                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <form action="" method="">


                                    <!-- Full Name Field -->
                                    <div class="form-group">
                                        <label class="form-label" for="cus_name">Customer Name</label>
                                        <input type="text" name="cus_name" class="form-control" value="" required>
                                    </div>

                                    <!-- Customer Address Field -->
                                    <div class="form-group">
                                        <label class="form-label" for="cus_address">Customer Address</label>
                                        <input type="text" name="cus_address" class="form-control" value="" required>
                                    </div>

                                       <!-- Phone Number Field -->
                                       <div class="form-group">
                                        <label class="form-label" for="cus_phonenumber">Phone Number</label>
                                        <input type="text" name="cus_phonenumber" class="form-control" value="" required>
                                    </div>


                                    <!-- Phone Number Field -->
                                    <div class="form-group">
                                        <label class="form-label" for="cus_phonenumber">Phone Number</label>
                                        <input type="text" name="cus_phonenumber" class="form-control" value="" required>
                                    </div>

                                    <div class="form-group">
                                        <label class="form-label" for="cus_phonenumber">Phone Number</label>
                                        <input type="text" name="cus_phonenumber" class="form-control" value="" required>
                                    </div>



                                    <!-- Save Button -->
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-lg btn-primary">Save Changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
