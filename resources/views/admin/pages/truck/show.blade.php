<x-app-layout>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <h3 class="nk-block-title page-title">Truck Details</h3>
                    <div class="card">
                        <div class="card-body">
                            <p><strong>Truck Name:</strong> {{ $truck->truck_name }}</p>
                            <p><strong>Truck Type:</strong> {{ $truck->truck_type }}</p>
                            <p><strong>Status:</strong> {{ $truck->truck_status }}</p>
                            <a href="{{ route('truck.index') }}" class="btn btn-secondary">Back to List</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</x-app-layout>

