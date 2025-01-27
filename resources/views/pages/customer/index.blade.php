            <x-app-layout>
                   <div class="nk-content ">
                    <div class="container-fluid">
                        <div class="nk-content-inner">
                            <div class="nk-content-body">
                                <div class="nk-block-head nk-block-head-sm">
                                    <div class="nk-block-between">
                                        <div class="nk-block-head-content">
                                            <h3 class="nk-block-title page-title">Customers</h3>
                                        </div><!-- .nk-block-head-content -->
                                        <div class="nk-block-head-content">
                                            <div class="toggle-wrap nk-block-tools-toggle">
                                                <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="more-options"><em class="icon ni ni-more-v"></em></a>
                                                <div class="toggle-expand-content" data-content="more-options">
                                                    <ul class="nk-block-tools g-3">


                                                        <li class="nk-block-tools-opt">
                                                            <!-- Buttons to trigger the modal -->
                                                            <a href="#" class="btn btn-icon btn-primary d-md-none" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                                <em class="icon ni ni-plus"></em>
                                                            </a>
                                                            <a href="#" class="btn btn-primary d-none d-md-inline-flex" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                                                <em class="icon ni ni-plus"></em><span>Add</span>
                                                            </a>

                                                            <!-- Modal -->
                                                            <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                                <div class="modal-dialog">
                                                                    <div class="modal-content">
                                                                        <!-- Modal Header -->
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title fw-bold" id="exampleModalLabel">Customer Info</h5>


                                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

                                                                        </div>
                                                                        <!-- Modal Body -->
                                                                        <div class="modal-body">
                                                                            <form action="{{ route('customer.save') }}" method="POST" class="form-validate is-alter" novalidate="novalidate">
                                                                                @csrf
                                                                                <!-- Full Name Field -->
                                                                                <div class="form-group">
                                                                                    <label class="form-label" for="full-name">Customer Name</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input type="text" name="cus_name" class="form-control" id="full-name" required>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- Customer Address Field -->
                                                                                <div class="form-group">
                                                                                    <label class="form-label">Customer Address</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input type="text" name="cus_address" class="form-control" required>
                                                                                    </div>
                                                                                </div>
                                                                                <!-- Phone Number Field -->
                                                                                <div class="form-group">
                                                                                    <label class="form-label" for="phone-no">Phone Number</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input type="text" name="cus_phonenumber" class="form-control" id="phone-no" required>
                                                                                    </div>
                                                                                </div>
                                                                                <div class="form-group">
                                                                                    <label class="form-label" for="bal">Customer Balance</label>
                                                                                    <div class="form-control-wrap">
                                                                                        <input type="text" name="cus_balance" class="form-control" id="bal" required>
                                                                                    </div>
                                                                                </div>




                                                                                <!-- Save Button -->
                                                                                <div class="form-group">
                                                                                    <button type="submit" class="btn btn-lg btn-primary">Save Information</button>&ensp;
                                                                                </div>
                                                                            </form>
                                                                        </div>

                                                                        <!-- Modal Footer -->
                                                                        <div class="modal-footer bg-light">
                                                                            <span class="sub-text">Modal Footer Text</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </li>

                                                    </ul>
                                                </div>
                                            </div>
                                        </div><!-- .nk-block-head-content -->
                                    </div><!-- .nk-block-between -->
                                </div><!-- .nk-block-head -->
                                <div class="nk-block nk-block-lg">

                                    <div class="card card-bordered card-preview">
                                        <div class="card-inner">
                                            <table class="datatable-init-export nowrap table" data-export-title="Export">
                                                <thead>
                                                    <tr>
                                                        <th>Customer ID</th>
                                                        <th>Customer Name</th>
                                                        <th>Phone Number</th>
                                                        <th>Customer Address</th>
                                                        <th>Balance</th>
                                                        <th>Actions</th> <!-- Added "Actions" column for Delete button -->
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($customers as $data)
                                                    <tr>
                                                        <td>{{ $data->cus_id }}</td>
                                                        <td>{{ $data->cus_name }}</td> <!-- Changed to cus_name -->
                                                        <td>{{ $data->cus_phonenumber }}</td>
                                                        <td>{{ $data->cus_address }}</td>
                                                        <td>{{ $data->cus_balance }}</td>
                                                        <td>
                                                            <div class="dropdown">
                                                                <a class="text-soft dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown" data-offset="-8,0" aria-expanded="false">
                                                                    <em class="icon ni ni-more-h"></em>
                                                                </a>
                                                                <div class="dropdown-menu dropdown-menu-end dropdown-menu-xs">
                                                                    <ul class="link-list-plain">
                                                                        <!-- Edit action -->
                                                                        <li><a href="{{ route('customer.edit', $data->cus_id) }}" class="text-primary">Edit</a></li>
                                                                        <!-- View action -->
                                                                        <li><a href="" class="text-primary">View</a></li>
                                                                        <!-- Delete action -->
                                                                        <li>
                                                                            <!-- SweetAlert delete link -->
                                                                            <a href="#" class="dropdown-item text-danger" onclick="event.preventDefault(); showDeleteConfirmation('{{ $data->cus_id }}');">
                                                                                Delete
                                                                            </a>

                                                                            <!-- Hidden delete form -->
                                                                            <form id="delete-form-{{ $data->cus_id }}" action="{{ route('customer.destroy', $data->cus_id) }}" method="POST" style="display: none;">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                            </form>

                                                                            <!-- SweetAlert Script -->
                                                                            <script>
                                                                                function showDeleteConfirmation(cus_id) {
                                                                                    Swal.fire({
                                                                                        title: 'Are you sure?',
                                                                                        text: "You won't be able to revert this!",
                                                                                        icon: 'warning',
                                                                                        showCancelButton: true,
                                                                                        confirmButtonColor: '#3085d6',
                                                                                        cancelButtonColor: '#d33',
                                                                                        confirmButtonText: 'Yes, delete it!',
                                                                                        cancelButtonText: 'Cancel'
                                                                                    }).then((result) => {
                                                                                        if (result.isConfirmed) {
                                                                                            // If confirmed, submit the form
                                                                                            document.getElementById('delete-form-' + cus_id).submit();
                                                                                        }
                                                                                    });
                                                                                }
                                                                            </script>
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
                                </div> <!-- nk-block -->


                    </div>
                </div>
            </x-app-layout>
