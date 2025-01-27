<x-app-layout>
    <div class="card">
        <div class="card-inner">
            <div class="card-head">
                <h5 class="card-title">Trucking Info</h5>
            </div>
            <form action="{{ route('trucking.store') }}" method="POST">
                @csrf
                <div class="row g-4">
                    <!-- Receipt ID -->
                    <!-- Receipt No (changed from Receipt ID) -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">Receipt No</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" value="{{ $receiptId }}" readonly>
                                <input type="hidden" name="receipt_no" value="{{ $receiptId }}">
                                <!-- Changed from receipt_id -->
                            </div>
                        </div>
                    </div>

                    <!-- Customer ID -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">Customer Name</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" value="{{ $customerName ?? '' }}" readonly>
                                <input type="hidden" name="CustomerID" value="{{ $customerId }}" required>
                                @error('CustomerID')
                                    <span class="invalid-feedback" style="display: block;">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <!-- Driver Selection -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">Select Driver</label>
                            <div class="form-control-wrap">
                                <select class="form-select js-select2" name="driver_id" required>
                                    <option selected disabled>Select</option>
                                    @foreach ($drivers as $driver)
                                        <option value="{{ $driver->driver_id }}">
                                            {{ $driver->fname }} {{ $driver->lname }}
                                            @if ($driver->status !== 'AVAILABLE')
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Truck Selection -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">Select Truck</label>
                            <div class="form-control-wrap">
                                <select class="form-select js-select2" name="truck_id" required>
                                    <option selected disabled>Select Truck</option>
                                    @foreach ($trucks as $truck)
                                        <option value="{{ $truck->truck_id }}">
                                            {{ $truck->truck_name }}
                                            @if ($truck->status !== 'Available')
                                            @endif
                                        </option>
                                    @endforeach
                                </select>
                                @if ($trucks->isEmpty())
                                    <div class="alert alert-warning mt-2">
                                        No trucks are currently available.
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <!-- Helper Selection -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">Select Helper</label>
                            <div class="form-control-wrap">
                                <select class="form-select js-select2" name="helper_id" required>
                                    <option selected disabled>Select</option>
                                    @foreach ($helpers as $helper)
                                        <option value="{{ $helper->helper_id }}">
                                            {{ $helper->fname }} {{ $helper->lname }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <!-- Allowance -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">Allowance</label>
                            <div class="form-control-wrap">
                                <input type="number" step="0.01" class="form-control" name="allowance"
                                    placeholder="Enter allowance amount" required>
                            </div>
                        </div>
                    </div>

                    <!-- Destination -->
                    <div class="col-lg-6">
                        <div class="form-group">
                            <label class="form-label">Destination</label>
                            <div class="form-control-wrap">
                                <input type="text" class="form-control" name="destination"
                                    placeholder="Enter destination" required>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="col-lg-12 text-end">
                        <button type="reset" class="btn btn-light bg-white">Reset</button>
                        <button type="submit" class="btn btn-primary"
                            onclick="console.log('Form Data:', new FormData(this.form))">Submit Record</button>
                    </div>
                </div>
            </form>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
    </div>
</x-app-layout>

