<div class="nk-sidebar nk-sidebar-fixed is-white" data-content="sidebarMenu">
    <!-- Sidebar Header -->
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-menu-trigger me-n2">
            <!-- Mobile toggle for sidebar -->
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu">
                <em class="icon ni ni-arrow-left"></em>
            </a>
            <!-- Desktop toggle for compact view -->
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="sidebarMenu">
                <em class="icon ni ni-menu"></em>
            </a>
        </div>

        <!-- Sidebar Brand Logo -->
        <div class="nk-sidebar-brand">
            <a href="/dashboard" class="logo-link nk-sidebar-logo">

                <img class="logo-dark" style="height: 70px;" src="{{ asset('/storage/logo.png') }}" alt="logo">

            </a>
        </div>

    </div>

    <!-- Sidebar Menu -->
    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    <!-- Menu Heading -->


                    @if (Auth::user()->role == 'Administrator')
                        <li class="nk-menu-heading pt-0">
                            <h6 class="overline-title text-primary-alt">Menu</h6>
                        </li>
                        <li class="nk-menu-item">
                            <a href="/dashboard" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-dashboard-fill"></em></span>
                                <span class="nk-menu-text">Dashboard</span>
                            </a>
                        </li>

                        <li class="nk-menu-heading pt-0">
                            <h6 class="overline-title text-primary-alt">Parties</h6>
                        </li>

                        <li class="nk-menu-item">
                            <a href="/admin/driver" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                                <span class="nk-menu-text">Driver</span>
                            </a>
                        </li>

                        <li class="nk-menu-item">
                            <a href="/admin/helper" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                                <span class="nk-menu-text">Helper</span>
                            </a>
                        </li>
                        <li class="nk-menu-heading pt-3">
                            <h6 class="overline-title text-primary-alt">Inventory Menu</h6>
                        </li>

                        <li class="nk-menu-item">
                            <a href="/admin/product" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-package-fill"></em></span>
                                <span class="nk-menu-text">Product</span>
                            </a>

                        </li>

                        <li class="nk-menu-heading pt-3">
                            <h6 class="overline-title text-primary-alt">Trucking Menu</h6>
                        </li>

                        <li class="nk-menu-item">
                            <a href="/admin/truck" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-truck"></em></span>
                                <span class="nk-menu-text">Truck List</span>
                            </a>
                        </li>

                        <li class="nk-menu-heading pt-3">
                            <h6 class="overline-title text-primary-alt">History</h6>
                        </li>
                        <li class="nk-menu-item">
                            <a href="/admin/history" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-history"></em></span>
                                <span class="nk-menu-text">Stock History</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="/admin/purchase/history" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-history"></em></span>
                                <span class="nk-menu-text">Purchase History</span>
                            </a>
                        </li>

                        <li class="nk-menu-heading pt-3">
                            <h6 class="overline-title text-primary-alt">REPORTS</h6>
                        </li>
                        <li class="nk-menu-item has-sub">
                            <a href="javascript:void(0);" class="nk-menu-link nk-menu-toggle">
                                <span class="nk-menu-icon"><em class="icon ni ni-trend-up"></em></span>
                                <span class="nk-menu-text">Reports</span>
                            </a>
                            <ul class="nk-menu-sub">
                                <li class="nk-menu-item">
                                    <a href="/admin/wholesale/reports" class="nk-menu-link">
                                        <span class="nk-menu-text">Sales Report</span>
                                    </a>
                                </li>

                                <li class="nk-menu-item">
                                    <a href="/admin/inventory/reports" class="nk-menu-link">
                                        <span class="nk-menu-text">Stocks Report </span>
                                    </a>
                                </li>
                                <li class="nk-menu-item">
                                    <a href="/admin/expenses/reports" class="nk-menu-link">
                                        <span class="nk-menu-text">Expense Report</span>
                                    </a>
                                </li>

                                {{-- <li class="nk-menu-item">
                                    <a href="/truck/reports" class="nk-menu-link">
                                        <span class="nk-menu-text">Trucks Report </span>
                                    </a>
                                </li> --}}

                            </ul>

                        <li class="nk-menu-heading pt-3">
                            <h6 class="overline-title text-primary-alt">PERMISSION</h6>
                        </li>
                        <li class="nk-menu-item">
                            <a href="/discount/code" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-user"></em></span>
                                <span class="nk-menu-text">Discount Code</span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->role == 'Clerk')
                        <li class="nk-menu-heading pt-0">
                            <h6 class="overline-title text-primary-alt">Stocks Adjustment</h6>
                        </li>
                        <li class="nk-menu-item">
                            <a href="/stocks" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-package-fill"></em></span>
                                <span class="nk-menu-text">Stock In</span>
                            </a>
                        </li>

                        <li class="nk-menu-item">
                            <a href="/clerk/stocks" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-package-fill"></em></span>
                                <span class="nk-menu-text">Stock List</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="/logs/stocks" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-package-fill"></em></span>
                                <span class="nk-menu-text">Stock Logs</span>
                            </a>
                        </li>
                        <li class="nk-menu-item">
                            <a href="/expired/products" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-package-fill"></em></span>
                                <span class="nk-menu-text">Stock Report</span>
                            </a>
                        </li>

                        <li class="nk-menu-item">
                            <a href="/condemn/stocks" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-package-fill"></em></span>
                                <span class="nk-menu-text">Condemn</span>
                            </a>
                        </li>
                    @endif

                    @if (Auth::user()->role == 'Cashier')
                        <li class="nk-menu-heading pt-0">
                            <h6 class="overline-title text-primary-alt">Sales Menu</h6>
                        </li>

                        <li class="nk-menu-item">
                            <a href="/cashier/pos" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-cart-fill"></em></span>
                                <span class="nk-menu-text">POS</span>
                            </a>
                        </li>

                        <li class="nk-menu-heading pt-0">
                            <h6 class="overline-title text-primary-alt">Trucking Menu</h6>
                        </li>

                        <li class="nk-menu-item">
                            <a href="/cashier/trucking" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-truck"></em></span>
                                <span class="nk-menu-text">Delivery</span>
                            </a>
                        </li>

                        <li class="nk-menu-item">
                            <a href="/cashier/sales" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-file-docs"></em></span>
                                <span class="nk-menu-text">Sales</span>
                            </a>
                        </li>

                        <li class="nk-menu-item">
                            <a href="/cashier/expenses" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-money"></em></span>
                                <span class="nk-menu-text">Expenses</span>
                            </a>
                        </li>

                        <li class="nk-menu-item">
                            <a href="/cashier/customer" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-users"></em></span>
                                <span class="nk-menu-text">Customers</span>
                            </a>
                        </li>
                        {{-- <li class="nk-menu-item">
                            <a href="/cashier/collection" class="nk-menu-link">
                                <span class="nk-menu-icon"><em class="icon ni ni-wallet-fill"></em></span>
                                <span class="nk-menu-text">Collection</span>
                            </a>
                        </li> --}}
                    @endif

                    <!-- Add more menu items here -->
                </ul>
            </div>
        </div>
    </div>
</div>
