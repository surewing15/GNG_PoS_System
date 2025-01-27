<x-app-layout>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <h3 class="nk-block-title page-title">Edit Truck</h3>
                    <div class="card">
                        <div class="card-body">
                            <form action="{{ route('truck.update', $truck->truck_id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="truck_name">Truck Name</label>
                                    <input type="text" class="form-control" id="truck_name" name="truck_name"
                                        value="{{ $truck->truck_name }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="truck_type">Truck Type</label>
                                    <input type="text" class="form-control" id="truck_type" name="truck_type"
                                        value="{{ $truck->truck_type }}" required>
                                </div>
                                <div class="form-group">
                                    <label for="truck_status">Status</label>
                                    <select class="form-control" id="truck_status" name="truck_status" required>
                                        <option value="Available"
                                            {{ $truck->truck_status == 'Available' ? 'selected' : '' }}>Available</option>
                                        <option value="Not Available"
                                            {{ $truck->truck_status == 'Not Available' ? 'selected' : '' }}>Not Available
                                        </option>
                                        <option value="In Maintenance"
                                            {{ $truck->truck_status == 'In Maintenance' ? 'selected' : '' }}>In Maintenance
                                        </option>
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary">Update Truck</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

