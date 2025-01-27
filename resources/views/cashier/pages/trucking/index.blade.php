<x-app-layout>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <!-- Truck List Section -->
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between align-items-center">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Delivery List</h3>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="form-group">
                                    <div class="form-control-wrap">
                                        <select id="statusFilter" class="form-select form-control-lg">
                                            <option value="" {{ request('status') == '' ? 'selected' : '' }}>All</option>
                                            <option value="Not Assigned" {{ request('status') == 'Not Assigned' ? 'selected' : '' }}>Not Assigned</option>
                                            <option value="On Going" {{ request('status') == 'On Going' ? 'selected' : '' }}>On Going</option>
                                            <option value="Successful" {{ request('status') == 'Successful' ? 'selected' : '' }}>Successful</option>
                                        </select>

                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>





                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <table class="datatable-init nowrap table clickable-table" data-export-title="Export" data-order="false">
                                    <thead>
                                        <tr>
                                            <th>Receipt ID #</th>
                                            <th>Customer Name</th>
                                            <th>Date</th>
                                            <th>Service Type</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($deliverys->sortByDesc('date') as $delivery)
                                        <tr>
                                            <td>{{ $delivery->receipt_id }}</td>
                                            <td>{{ $delivery->customer->FirstName ?? 'N/A' }}
                                                {{ $delivery->customer->LastName ?? '' }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($delivery->created_at)->format('F j, Y g:i A') }}
                                            </td>

                                            <td>{{ $delivery->service_type }}</td>
                                            <td>
                                                <span
                                                    class="badge
                                                    {{ $delivery->status == 'Not Assigned'
                                                        ? 'bg-warning text-dark'
                                                        : ($delivery->status == 'Successful'
                                                            ? 'bg-success text-white'
                                                            : ($delivery->status == 'On Going'
                                                                ? 'bg-primary text-white'
                                                                : 'bg-info text-white')) }}">
                                                    {{ $delivery->status }}
                                                </span>
                                            </td>

                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-icon btn-trigger" type="button" data-bs-toggle="dropdown">
                                                        <em class="icon ni ni-more-h"></em>
                                                    </button>
                                                    <ul class="dropdown-menu dropdown-menu-end">
                                                        @if ($delivery->status != 'Delivered')
                                                            <li>
                                                                <a class="dropdown-item"
                                                                    href="{{ route('trucking.create', ['receipt_id' => $delivery->receipt_id, 'customer_name' => $delivery->customer_name]) }}">
                                                                    <em class="icon ni ni-pen"></em>
                                                                    <span>Assign</span>
                                                                </a>
                                                            </li>
                                                        @endif
                                                        <li>
                                                            <a class="dropdown-item view-details" href="#" data-receipt-id="{{ $delivery->receipt_id }}">
                                                                <em class="icon ni ni-alert-circle"></em>
                                                                <span>View</span>
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="#" id="markDelivered">
                                                                <em class="icon ni ni-alert-circle"></em>
                                                                <span>Mark as Delivered</span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                        @endforeach

                                    </tbody>
                                </table>
                                <div class="mt-3">
                                    {{ $deliverys->appends(['status' => request('status')])->links('pagination::bootstrap-5') }}
                                </div>
<script>// Delivery Status Filtering Script

document.addEventListener('DOMContentLoaded', function () {
    const statusFilter = document.getElementById('statusFilter');

    // Handle filter change
    statusFilter.addEventListener('change', function () {
        const selectedStatus = statusFilter.value;

        // Redirect to the server-side route with the selected filter and reset to page 1
        const url = new URL(window.location.href);
        url.searchParams.set('status', selectedStatus);
        url.searchParams.set('page', 1); // Reset pagination to page 1
        window.location.href = url.toString();
    });
});

    </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <!-- Modal -->
    <div class="modal fade" id="detailsModal" tabindex="-1" aria-labelledby="detailsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="detailsModalLabel">Delivery Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div id="loading-message" style="display: none;">
                        Loading delivery details...
                    </div>
                    <div id="error-message" style="display: none;" class="alert alert-danger">
                    </div>
                    <div id="delivery-details">
                        <div class="mb-3">
                            <strong>Receipt ID:</strong> <span id="modal-receipt-id">-</span>
                        </div>
                        <div class="mb-3">
                            <strong>Customer Name:</strong> <span id="modal-customer-name">-</span>
                        </div>
                        <div class="mb-3">
                            <strong>Driver Name:</strong> <span id="modal-driver-name">-</span>
                        </div>
                        <div class="mb-3">
                            <strong>Helper Name:</strong> <span id="modal-helper-name">-</span>
                        </div>
                        <div class="mb-3">
                            <strong>Truck Name:</strong> <span id="modal-truck-name">-</span>
                        </div>
                        <div class="mb-3">
                            <strong>Allowance:</strong> <span id="modal-allowance">-</span>
                        </div>
                        <div class="mb-3">
                            <strong>Destination:</strong> <span id="modal-destination">-</span>
                        </div>
                        <div class="mb-3">
                            <strong>Status:</strong> <span id="modal-status">-</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


@section('scripts')
    <script>


        $(document).ready(function() {
            // Initialize dropdowns
            var dropdownElementList = [].slice.call(document.querySelectorAll('.dropdown-toggle'))
            var dropdownList = dropdownElementList.map(function(dropdownToggleEl) {
                return new bootstrap.Dropdown(dropdownToggleEl)
            });

            // View details click handler
            $('.view-details').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();

                const receiptId = $(this).data('receipt-id');
                console.log('View details clicked for:', receiptId);

                // Show loading message
                $('#loading-message').show();
                $('#delivery-details').hide();
                $('#error-message').hide();

                // Make AJAX request
                $.ajax({
                    url: '/delivery/' + receiptId + '/details',
                    method: 'GET',
                    success: function(response) {
                        console.log('Response:', response);

                        $('#loading-message').hide();
                        $('#delivery-details').show();

                        // Update modal content
                        $('#modal-receipt-id').text(response.receipt_id || '-');
                        $('#modal-customer-name').text(response.customer_name || '-');
                        $('#modal-driver-name').text(response.driver_name || '-');
                        $('#modal-helper-name').text(response.helper_name || '-');
                        $('#modal-truck-name').text(response.truck_name || '-');
                        $('#modal-allowance').text(response.allowance || '-');
                        $('#modal-destination').text(response.destination || '-');
                        $('#modal-status').text(response.status || '-');

                        // Show modal
                        var modal = new bootstrap.Modal(document.getElementById(
                            'detailsModal'));
                        modal.show();
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        console.error('Response:', xhr.responseText);

                        $('#loading-message').hide();
                        $('#error-message')
                            .text('Error loading delivery details: ' + error)
                            .show();
                    }
                });
            });

            // Attach click handler to document for dynamically loaded elements
            $(document).on('click', '.view-details', function(e) {
                e.preventDefault();
                e.stopPropagation();

                $(this).trigger('click');
            });
        });
        $(document).on('click', '.dropdown-menu .dropdown-item:contains("Mark as Delivered")', function(e) {
    e.preventDefault();
    const row = $(this).closest('tr');
    const receiptId = row.find('td:first').text(); // Get receipt ID from the row

    if (confirm('Are you sure you want to mark this delivery as completed?')) {
        $.ajax({
            url: '/delivery/update-status',
            method: 'POST',
            data: {
                receipt_id: receiptId,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    row.find('.badge')
                        .removeClass('bg-warning bg-info text-dark')
                        .addClass('bg-success text-white')
                        .text('Successful');

                    location.reload();
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr, status, error) {
                alert('Error updating delivery status: ' + error);
            }
        });
    }
});
    </script>
@endsection

</x-app-layout>
