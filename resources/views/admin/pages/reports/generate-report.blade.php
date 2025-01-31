<x-app-layout>
    <div class="nk-content">
        <div class="container-fluid">
            <div class="nk-content-inner">
                <div class="nk-content-body">

                    <div class="nk-block-head nk-block-head-sm">
                        <div class="nk-block-between">
                            <div class="nk-block-head-content">
                                <h3 class="nk-block-title page-title">Daily Sales Report</h3>
                                <div class="nk-block-des text-soft">
                                    <p>Detailed overview of daily transactions</p>
                                </div>
                            </div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <div class="toggle-expand-content">
                                        <ul class="nk-block-tools g-3">
                                            <li>
                                                <div class="form-control-wrap">
                                                    <div class="form-icon form-icon-right">
                                                        <em class="icon ni ni-calendar"></em>
                                                    </div>
                                                    <input type="text" class="form-control date-picker"
                                                        id="datepicker" placeholder="dd/mm/yyyy">
                                                </div>
                                            </li>
                                            <li class="nk-block-tools-opt">
                                                <button onclick="exportReport()" class="btn btn-primary">
                                                    <em class="icon ni ni-download-cloud"></em>
                                                    <span>Export to Excel</span>
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="nk-block">
                        <div class="card card-bordered">
                            <div class="card-inner">
                                <div class="card-title-group align-start mb-2">
                                    <div class="card-title">
                                        <h6 class="title">Total Cash Sales</h6>
                                    </div>
                                </div>
                                <div class="card-amount">
                                    <span class="amount">₱{{ number_format($totalCashSales, 2) }}</span>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="nk-block">
                        <div class="card card-bordered card-stretch">
                            <div class="card-inner-group">
                                <div class="card-inner position-relative">
                                    <div class="card-title-group">
                                        <div class="card-tools">
                                            <div class="form-inline flex-nowrap gx-3">
                                                <div class="form-wrap w-150px">
                                                    <input type="text" class="form-control" id="searchInput"
                                                        placeholder="Search transactions">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-inner p-0">
                                    <div class="nk-tb-list nk-tb-ulist">
                                        <div class="nk-tb-item nk-tb-head">
                                            <div class="nk-tb-col"><span class="sub-text">Customer Name</span></div>
                                            <div class="nk-tb-col tb-col-mb"><span class="sub-text">Receipt #</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-md"><span class="sub-text">Amount</span></div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text">Payment Type</span>
                                            </div>
                                            <div class="nk-tb-col tb-col-lg"><span class="sub-text">Date</span></div>
                                        </div>

                                        @foreach ($transactions as $transaction)
                                            <div class="nk-tb-item">
                                                <div class="nk-tb-col">
                                                    <span class="tb-lead">{{ $transaction->FirstName }}
                                                        {{ $transaction->LastName }}</span>
                                                </div>
                                                <div class="nk-tb-col tb-col-mb">
                                                    <span class="tb-amount">{{ $transaction->receipt_id }}</span>
                                                </div>
                                                <div class="nk-tb-col tb-col-md">
                                                    <span
                                                        class="tb-amount">₱{{ number_format($transaction->final_amount, 2) }}</span>
                                                </div>
                                                <div class="nk-tb-col tb-col-lg">
                                                    <span
                                                        class="badge badge-dot {{ $transaction->payment_type === 'cash' ? 'badge-success' : 'badge-primary' }}">
                                                        {{ ucfirst($transaction->payment_type) }}
                                                    </span>
                                                </div>
                                                <div class="nk-tb-col tb-col-lg">
                                                    <span
                                                        class="tb-date">{{ Carbon\Carbon::parse($transaction->created_at)->format('M d, Y') }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <div class="card-inner">
                                    <div class="nk-block-between-md g-3">
                                        <div class="g">
                                            <ul class="pagination justify-content-center justify-content-md-start">
                                                <li
                                                    class="page-item {{ $transactions->onFirstPage() ? 'disabled' : '' }}">
                                                    <a class="page-link"
                                                        href="{{ $transactions->appends(['date' => request('date')])->previousPageUrl() }}">
                                                        <em class="icon ni ni-chevrons-left"></em>
                                                    </a>
                                                </li>
                                                <li class="page-item active">
                                                    <a class="page-link" href="#">1</a>
                                                </li>
                                                <li
                                                    class="page-item {{ !$transactions->hasMorePages() ? 'disabled' : '' }}">
                                                    <a class="page-link"
                                                        href="{{ $transactions->appends(['date' => request('date')])->nextPageUrl() }}">
                                                        <em class="icon ni ni-chevrons-right"></em>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="g">
                                            <div
                                                class="pagination-goto d-flex justify-content-center justify-content-md-start gx-3">
                                                <div class="text-muted">1-1 of 1</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('.date-picker').datepicker({
                format: 'dd/mm/yyyy',
                autoclose: true,
                todayHighlight: true,
                orientation: 'bottom',
                clearBtn: true,
                templates: {
                    leftArrow: '<em class="icon ni ni-chevron-left"></em>',
                    rightArrow: '<em class="icon ni ni-chevron-right"></em>'
                }
            }).on('changeDate', function(e) {
                if (!e.date) return;
                const formattedDate = formatDateForBackend(e.date);
                window.location.href = `/admin/generate/reports?date=${formattedDate}`;
            });

            // Only set date if it's in the URL
            const urlParams = new URLSearchParams(window.location.search);
            const dateParam = urlParams.get('date');

            if (dateParam) {
                const [year, month, day] = dateParam.split('-');
                const formattedDate = `${day}/${month}/${year}`;
                $('.date-picker').datepicker('setDate', formattedDate);
            }
        });

        // Add the missing formatDateForBackend function
        function formatDateForBackend(date) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }



        function exportReport() {
            try {
                const datePicker = document.querySelector('.date-picker');
                const selectedDate = $(datePicker).datepicker('getDate');

                if (!selectedDate) {
                    throw new Error('Please select a valid date');
                }

                const formattedDate = formatDateForBackend(selectedDate);
                const exportUrl = `/reports/${formattedDate}/export`;


                fetch(exportUrl)
                    .then(response => {
                        if (!response.ok) {
                            throw new Error('Export failed');
                        }
                        window.location.href = exportUrl;
                    })
                    .catch(error => {
                        console.error('Export error:', error);
                        alert('Failed to export report. Please try again.');
                    });
            } catch (error) {
                console.error('Date processing error:', error);
                alert(error.message);
            }
        }
    </script>
</x-app-layout>
