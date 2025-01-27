@extends('cashier.theme.layout')
@section('content')
    <div class="nk-content-inner">
        <div class="nk-content-body">
            <div class="nk-block-head nk-block-head-sm">
                <div class="nk-block-between">
                    <div class="nk-block-head-content">
                        <h3 class="nk-block-title page-title">Dashboard</h3>
                    </div><!-- .nk-block-head-content -->
                    <div class="nk-block-head-content">
                        <div class="toggle-wrap nk-block-tools-toggle">
                            <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em
                                    class="icon ni ni-more-v"></em></a>
                            <div class="toggle-expand-content" data-content="pageMenu">
                                <ul class="nk-block-tools g-3">
                                    <li>
                                        <div class="drodown">
                                            <a href="#"
                                                class="dropdown-toggle btn btn-white btn-dim btn-outline-light"
                                                data-bs-toggle="dropdown"><em
                                                    class="d-none d-sm-inline icon ni ni-calender-date"></em><span><span
                                                        class="d-none d-md-inline">Last</span> 30 Days</span><em
                                                    class="dd-indc icon ni ni-chevron-right"></em></a>
                                            <div class="dropdown-menu dropdown-menu-end">
                                                <ul class="link-list-opt no-bdr">
                                                    <li><a href="#"><span>Last 30 Days</span></a></li>
                                                    <li><a href="#"><span>Last 6 Months</span></a></li>
                                                    <li><a href="#"><span>Last 1 Years</span></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="nk-block-tools-opt"><a href="#" class="btn btn-primary"><em
                                                class="icon ni ni-reports"></em><span>Reports</span></a></li>
                                </ul>
                            </div>
                        </div>
                    </div><!-- .nk-block-head-content -->
                </div><!-- .nk-block-between -->
            </div><!-- .nk-block-head -->
            <div class="nk-block">
                <div class="row g-gs">

                    <div class="col-xxl-3 col-md-6">
                        <div class="card is-dark h-100">
                            <div class="nk-ecwg nk-ecwg1">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title">
                                            <h6 class="title">Total Sales</h6>
                                        </div>
                                        <div class="card-tools">
                                            <a href="#" class="link">View Report</a>
                                        </div>
                                    </div>
                                    <div class="data">
                                        <div class="amount">$74,958.49</div>
                                        <div class="info"><strong>$7,395.37</strong> in last month</div>
                                    </div>
                                    <div class="data">
                                        <h6 class="sub-title">This week so far</h6>
                                        <div class="data-group">
                                            <div class="amount">$1,338.72</div>
                                            <div class="info text-end"><span class="change up text-danger"><em
                                                        class="icon ni ni-arrow-long-up"></em>4.63%</span><br><span>vs. last
                                                    week</span></div>
                                        </div>
                                    </div>
                                </div><!-- .card-inner -->
                                <div class="nk-ecwg1-ck">
                                    <div class="chartjs-size-monitor">
                                        <div class="chartjs-size-monitor-expand">
                                            <div class=""></div>
                                        </div>
                                        <div class="chartjs-size-monitor-shrink">
                                            <div class=""></div>
                                        </div>
                                    </div>
                                    <canvas class="ecommerce-line-chart-s1 chartjs-render-monitor" id="totalSales"
                                        width="479" height="110"
                                        style="display: block; width: 479px; height: 110px;"></canvas>
                                </div>
                            </div><!-- .nk-ecwg -->
                        </div><!-- .card -->
                    </div>

                    <div class="col-xxl-3 col-md-6">
                        <div class="card is-dark h-100">
                            <div class="nk-ecwg nk-ecwg1">
                                <div class="card-inner">
                                    <div class="card-title-group">
                                        <div class="card-title">
                                            <h6 class="title">Total Less</h6>
                                        </div>
                                        <div class="card-tools">
                                            <a href="#" class="link">View Report</a>
                                        </div>
                                    </div>
                                    <div class="data">
                                        <div class="amount">$74,958.49</div>
                                        <div class="info"><strong>$7,395.37</strong> in last month</div>
                                    </div>
                                    <div class="data">
                                        <h6 class="sub-title">This week so far</h6>
                                        <div class="data-group">
                                            <div class="amount">$1,338.72</div>
                                            <div class="info text-end"><span class="change up text-danger"><em
                                                        class="icon ni ni-arrow-long-up"></em>4.63%</span><br><span>vs. last
                                                    week</span></div>
                                        </div>
                                    </div>
                                </div><!-- .card-inner -->
                                <div class="nk-ecwg1-ck">
                                    <div class="chartjs-size-monitor">
                                        <div class="chartjs-size-monitor-expand">
                                            <div class=""></div>
                                        </div>
                                        <div class="chartjs-size-monitor-shrink">
                                            <div class=""></div>
                                        </div>
                                    </div>
                                    <canvas class="ecommerce-line-chart-s1 chartjs-render-monitor" id="totalSales"
                                        width="479" height="110"
                                        style="display: block; width: 479px; height: 110px;"></canvas>
                                </div>
                            </div><!-- .nk-ecwg -->
                        </div><!-- .card -->
                    </div>
                    <div class="col-xxl-3 col-md-6">
                        <div class="card card-full overflow-hidden">
                            <div class="nk-ecwg nk-ecwg7 h-100">
                                <div class="card-inner flex-grow-1">
                                    <div class="card-title-group mb-4">
                                        <div class="card-title">
                                            <h6 class="title">Order Statistics</h6>
                                        </div>
                                    </div>
                                    <div class="nk-ecwg7-ck">
                                        <div class="chartjs-size-monitor">
                                            <div class="chartjs-size-monitor-expand">
                                                <div class=""></div>
                                            </div>
                                            <div class="chartjs-size-monitor-shrink">
                                                <div class=""></div>
                                            </div>
                                        </div>
                                        <canvas class="ecommerce-doughnut-s1 chartjs-render-monitor" id="orderStatistics"
                                            width="180" height="180"
                                            style="display: block; width: 180px; height: 180px;"></canvas>
                                    </div>
                                    <ul class="nk-ecwg7-legends">
                                        <li>
                                            <div class="title">
                                                <span class="dot dot-lg sq" data-bg="#816bff"
                                                    style="background: rgb(129, 107, 255);"></span>
                                                <span>Completed</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">
                                                <span class="dot dot-lg sq" data-bg="#13c9f2"
                                                    style="background: rgb(19, 201, 242);"></span>
                                                <span>Processing</span>
                                            </div>
                                        </li>
                                        <li>
                                            <div class="title">
                                                <span class="dot dot-lg sq" data-bg="#ff82b7"
                                                    style="background: rgb(255, 130, 183);"></span>
                                                <span>Cancelled</span>
                                            </div>
                                        </li>
                                    </ul>
                                </div><!-- .card-inner -->
                            </div>
                        </div><!-- .card -->
                    </div><!-- .col -->
                    <div class="col-xxl-3 col-md-6">
                        <div class="card h-100">
                            <div class="card-inner">
                                <div class="card-title-group mb-2">
                                    <div class="card-title">
                                        <h6 class="title">Sales</h6>
                                    </div>
                                </div>
                                <ul class="nk-store-statistics">
                                    <li class="item">
                                        <div class="info">
                                            <div class="title">Fresh And Chicken Total Sales</div>
                                            <div class="count">1,795</div>
                                        </div>
                                        <em class="icon bg-primary-dim ni ni-bag"></em>
                                    </li>
                                    <li class="item">
                                        <div class="info">
                                            <div class="title">Egg Total Sales</div>
                                            <div class="count">2,327</div>
                                        </div>
                                        <em class="icon bg-info-dim ni ni-users"></em>
                                    </li>
                                    <li class="item">
                                        <div class="info">
                                            <div class="title">Return Cash</div>
                                            <div class="count">674</div>
                                        </div>
                                        <em class="icon bg-pink-dim ni ni-box"></em>
                                    </li>
                                    <li class="item">
                                        <div class="info">
                                            <div class="title">Total Sales</div>
                                            <div class="count">10200</div>
                                        </div>
                                        <em class="icon bg-purple-dim ni ni-server"></em>
                                    </li>
                                </ul>
                            </div><!-- .card-inner -->
                        </div><!-- .card -->
                    </div><!-- .col -->

                    <div class="col-xxl-3 col-md-6">
                        <div class="card h-100">
                            <div class="card-inner">
                                <div class="card-title-group mb-2">
                                    <div class="card-title">
                                        <h6 class="title">Less</h6>
                                    </div>
                                </div>
                                <ul class="nk-store-statistics">
                                    <li class="item">
                                        <div class="info">
                                            <div class="title">Deposit Truchef Partial</div>
                                            <div class="count">1,795</div>
                                        </div>
                                        <em class="icon bg-primary-dim ni ni-bag"></em>
                                    </li>
                                    <li class="item">
                                        <div class="info">
                                            <div class="title">Expenses</div>
                                            <div class="count">2,327</div>
                                        </div>
                                        <em class="icon bg-info-dim ni ni-users"></em>
                                    </li>
                                    <li class="item">
                                        <div class="info">
                                            <div class="title">Credit/Bal</div>
                                            <div class="count">674</div>
                                        </div>
                                        <em class="icon bg-pink-dim ni ni-box"></em>
                                    </li>
                                    <li class="item">
                                        <div class="info">
                                            <div class="title">Total Less</div>
                                            <div class="count">10200</div>
                                        </div>
                                        <em class="icon bg-purple-dim ni ni-server"></em>
                                    </li>
                                </ul>
                            </div><!-- .card-inner -->
                        </div><!-- .card -->
                    </div><!-- .col -->
                    <div class="col-xxl-5">
                        <div class="card card-full">
                            <div class="card-inner">
                                <div class="card-title-group">
                                    <div class="card-title">
                                        <h6 class="title">Denomination</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mt-2">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Cash</th>
                                            <th>Pieces</th>
                                            <th>Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="#" class="text-decoration-none">#1</a></td>
                                            <td>2000</td>
                                            <td>1202</td>
                                            <td>4,596.7 <span>PESOS</span></td>
                                        </tr>
                                        <!-- Add more rows as needed -->
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .card -->
                    </div><!-- .col -->
                    <div class="col-xxl-4 col-md-8 col-lg-6">
                        <div class="card h-100">
                            <div class="card-inner">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="title">Fresh Dressed Chicken And Product Expenses</h6>
                                    <a href="#" class="text-decoration-none">Amount</a>
                                </div>
                                <div class="mb-3">
                                    <a href="#" class="text-decoration-none">Description</a>
                                </div>
                                <ul class="list-unstyled">
                                    <li class="d-flex justify-content-between align-items-center py-2 border-bottom">
                                        <div class="info">
                                            <div class="fw-bold">Allowance Loading</div>

                                        </div>
                                        <div class="total text-end">
                                            <div class="fw-bold">$990.00</div>

                                        </div>
                                    </li>
                                    <!-- Add more product list items as needed -->
                                </ul>
                            </div><!-- .card-inner -->
                        </div><!-- .card -->
                    </div><!-- .col -->
                    <div class="col-xxl-8">
                        <div class="card card-full">
                            <div class="card-inner">
                                <div class="card-title-group">
                                    <div class="card-title">
                                        <h6 class="title">Fresh Dressed Chicken & Products Sales</h6>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive mt-2">
                                <table class="table table-hover align-middle">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Customer Name</th>
                                            <th>Series #</th>
                                            <th>Cash Sales</th>
                                            <th>Bal Payment</th>
                                            <th>Online</th>
                                            <th>Credit/Charge</th>
                                            <th>Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><a href="#" class="text-decoration-none">#1</a></td>
                                            <td>King Pax</td>
                                            <td>20001</td>
                                            <td>4,990</td>
                                            <td>100,5</td>
                                            <td>96,893.00</td>
                                            <td>1002,00</td>
                                            <td>Cheque</td>

                                        </tr>
                                        <!-- Add more rows as needed -->
                                    </tbody>
                                </table>
                            </div>
                        </div><!-- .card -->
                    </div><!-- .col -->

                </div><!-- .row -->
            </div><!-- .nk-block -->
        </div>
    </div>
@endsection
