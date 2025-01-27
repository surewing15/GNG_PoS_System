<x-app-layout>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between d-flex justify-content-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Truck List</h3>
                            </div>
                            <!-- Add Truck Button -->
                            <div class="nk-block-tools ms-auto d-flex align-items-center">
                                <ul class="nk-block-tools g-3 mb-0">
                                    <li class="nk-block-tools-opt">
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#truckModal" class="btn btn-icon btn-primary d-md-none">
                                            <em class="icon ni ni-plus"></em>
                                        </a>
                                        <a href="#" data-bs-toggle="modal" data-bs-target="#truckModal" class="btn btn-primary d-none d-md-inline-flex">
                                            <em class="icon ni ni-plus"></em><span>Add Truck</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->

                    <!-- Success Message -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <table class="datatable-init-export nowrap table" data-export-title="Export">
                                    <thead>
                                        <tr>
                                            <th>ID #</th>
                                            <th>Truck Name</th>
                                            <th>Truck Type</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($trucks as $truck)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $truck->truck_name }}</td>
                                                <td>{{ $truck->truck_type }}</td>
                                                <td>{{ $truck->truck_status }}</td>
                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <em class="icon ni ni-more-h"></em>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <ul class="link-list-opt no-bdr">
                                                                <!-- View -->
                                                                <li>
                                                                    <a href="{{ route('truck.show', $truck->truck_id) }}">
                                                                        <em class="icon ni ni-eye"></em><span>View</span>
                                                                    </a>
                                                                </li>
                                                                <!-- Edit -->
                                                                <li>
                                                                    <a href="{{ route('truck.edit', $truck->truck_id) }}">
                                                                        <em class="icon ni ni-pen"></em><span>Edit</span>
                                                                    </a>
                                                                </li>
                                                                <!-- Delete -->
                                                                <li>
                                                                    <form action="{{ route('truck.destroy', $truck->truck_id) }}" method="POST" style="display: inline;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="btn btn-link text-decoration-none text-danger">
                                                                            <em class="icon ni ni-trash"></em><span>Delete</span>
                                                                        </button>
                                                                    </form>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .card-preview -->
                    </div><!-- .nk-block -->
                </div>
            </div>
        </div>
    </div>

    <!-- Truck Modal -->
    @include('admin.forms.create-truck-modal')
</x-app-layout>
