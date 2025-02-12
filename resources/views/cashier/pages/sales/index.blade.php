<x-app-layout>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">
                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Sales</h3>
                            </div><!-- .nk-block-head-content -->

                            <div class="nk-block-head-content">

                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1"
                                        data-bs-toggle="collapse" data-bs-target="#more-options">
                                        <em class="icon ni ni-more-v"></em>
                                    </a>
                                    <div class="collapse" id="more-options">
                                        <!-- Additional options can be added here -->
                                    </div>
                                </div>
                            </div><!-- .nk-block-head-content -->
                        </div><!-- .nk-block-between -->
                    </div><!-- .nk-block-head -->

                    <div class="nk-block nk-block-lg">
                        <div class="card card-bordered card-preview">
                            <div class="card-inner">
                                <table class="datatable-init-export nowrap table" data-export-title="Export"
                                    data-order="false">
                                    <thead>
                                        <tr>
                                            <th>Receipt ID#</th>
                                            <th>Customer</th>
                                            <th>Total Amount</th>
                                            <th>Service Type</th>
                                            <th>Date</th>
                                            <th>Time</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($transactions as $transaction)
                                            <tr>
                                                <td>{{ $transaction['receipt_id'] }}</td>
                                                <td>{{ $transaction['customer'] }}</td>
                                                <td>{{ number_format($transaction['total_amount'], 2) }}</td>
                                                <td>{{ $transaction['service_type'] }}</td>
                                                <td>{{ \Carbon\Carbon::parse($transaction['created_at'])->format('F d, Y') }}
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($transaction['created_at'])->format('h:i:s A') }}
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <a href="#"
                                                            class="dropdown-toggle btn btn-icon btn-trigger"
                                                            data-bs-toggle="dropdown" aria-expanded="false">
                                                            <em class="icon ni ni-more-h"></em>
                                                        </a>
                                                        <div class="dropdown-menu dropdown-menu-end">
                                                            <ul class="link-list-opt no-bdr">
                                                                <li>
                                                                    <a href="#" class="move-to-delivery"
                                                                        data-receipt-id="{{ $transaction['receipt_id'] }}"
                                                                        data-bs-toggle="modal"
                                                                        data-bs-target="#moveToDeliveryModal">
                                                                        <em class="icon ni ni-file"></em>
                                                                        <span>Move to Delivery</span>
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a href="#" class="view-transaction-details"
                                                                        data-receipt-id="{{ $transaction['receipt_id'] }}">
                                                                        <em
                                                                            class="icon ni ni-edit"></em><span>View</span>
                                                                    </a>

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
        </div>
    </div>
    @include('cashier.modal.move-modal')
    @include('cashier.modal.cashier-modal')
</x-app-layout>
@section('scripts')
    <script>
        $(document).ready(function() {
            // Check if DataTable is already initialized
            if (!$.fn.DataTable.isDataTable('.datatable-init-export')) {
                // DataTable initialization
                NioApp.DataTable('.datatable-init-export', {
                    order: [
                        [3, 'desc'],
                        [4, 'desc']
                    ],
                    buttons: ['copy', 'excel', 'pdf', 'print'],
                    pageLength: 10,
                    lengthMenu: [
                        [10, 25, 50, -1],
                        [10, 25, 50, "All"]
                    ]
                });
            }

            var currentReceiptId = null;

            // When Move to Delivery is clicked
            $(document).on('click', '.move-to-delivery', function(e) {
                e.preventDefault();
                currentReceiptId = $(this).data('receipt-id');
                console.log('Selected receipt:', currentReceiptId);
            });

            // When confirm button is clicked
            $(document).on('click', '#moveToDeliveryModal .btn-primary', function() {
                console.log('Confirm clicked for receipt:', currentReceiptId);

                if (!currentReceiptId) {
                    alert('No transaction selected');
                    return;
                }

                $.ajax({
                    url: '/cashier/sales/update-service-type',
                    type: 'POST',
                    data: {
                        receipt_id: currentReceiptId
                    },
                    success: function(response) {
                        console.log('Response:', response);
                        $('#moveToDeliveryModal').modal('hide');

                        if (response.success) {
                            alert('Successfully moved to delivery');
                            window.location.reload();
                        } else {
                            alert(response.message || 'Error moving to delivery');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        console.log('Full error response:', xhr.responseText);
                        alert('Error updating transaction');
                    }
                });
            });
        });
    </script>
