<div class="nk-sidebar nk-sidebar-fixed is-light" data-content="sidebarMenu">
    <div class="nk-sidebar-element nk-sidebar-head">
        <div class="nk-sidebar-brand">
            <a href="html/index.html" class="logo-link nk-sidebar-logo">
                <img class="logo-dark" style="height: 50px;" src="/storage/logo.png" srcset="/storage/logo@2x.png 2x"
                    alt="logo">
            </a>
        </div>
        <div class="nk-menu-trigger me-n2">
            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu">
                <em class="icon ni ni-arrow-left"></em>
            </a>
            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex"
                data-target="sidebarMenu">
                <em class="icon ni ni-menu"></em>
            </a>
        </div>
    </div><!-- .nk-sidebar-element -->

    <div class="nk-sidebar-element">
        <div class="nk-sidebar-content">
            <div class="nk-sidebar-menu" data-simplebar>
                <ul class="nk-menu">
                    <li class="nk-menu-heading pt-0">
                        <h6 class="overline-title text-primary-alt">Dashboard</h6>
                    </li>
                    <li class="nk-menu-item">
                        <a href="/home" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-dashboard-fill"></em></span>
                            <span class="nk-menu-text">Dashboard</span>
                        </a>
                    </li>

                    <li class="nk-menu-heading pt-0">
                        <h6 class="overline-title text-primary-alt">Parties</h6>
                    </li>
                    {{-- <li class="nk-menu-item">
                        <a href="/admin/customer" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                            <span class="nk-menu-text">Customers</span>
                        </a>
                    </li> --}}
                    <li class="nk-menu-item">
                        <a href="{{ route('driver.index') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                            <span class="nk-menu-text">Driver</span>
                        </a>
                    </li>

                    <li class="nk-menu-item">
                        <a href="{{ route('helper.index') }}" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span>
                            <span class="nk-menu-text">Helper</span>
                        </a>
                    </li>
                    {{-- <li class="nk-menu-item">
                        <a href="/admin/order" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-cc-alt2-fill"></em></span>
                            <span class="nk-menu-text">Orders</span>
                        </a>
                    </li> --}}

                    <li class="nk-menu-heading pt-3">
                        <h6 class="overline-title text-primary-alt">Inventory Menu</h6>
                    </li>
                    <li class="nk-menu-item has-sub">
                        <a href="javascript:void(0);" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-package-fill"></em></span>
                            <span class="nk-menu-text">Product</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="/admin/product" class="nk-menu-link">
                                    <span class="nk-menu-text">All Products</span>
                                </a>
                            </li>
                        </ul>
                    </li>

                    {{-- <li class="nk-menu-item">
                        <a href="/admin/stocks" class="nk-menu-link">
                            <span class="nk-menu-icon"><em class="icon ni ni-package-fill"></em></span>
                            <span class="nk-menu-text">Stocks</span>
                        </a>

                    </li> --}}

                    <li class="nk-menu-heading pt-3">
                        <h6 class="overline-title text-primary-alt">Trucking Menu</h6>
                    </li>

                    <li class="nk-menu-item">
                        <a href="{{ route('truck.index') }}" class="nk-menu-link">
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
                        <a href="" class="nk-menu-link nk-menu-toggle">
                            <span class="nk-menu-icon"><em class="icon ni ni-trend-up"></em></span>
                            <span class="nk-menu-text">Reports</span>
                        </a>
                        <ul class="nk-menu-sub">
                            <li class="nk-menu-item">
                                <a href="/admin/wholesale/reports" class="nk-menu-link">
                                    <span class="nk-menu-text">Sales Report</span>
                                </a>
                            </li>

                            {{-- <li class="nk-menu-item">
                                <a href="/admin/denomination/reports" class="nk-menu-link">
                                    <span class="nk-menu-text">Denomination</span>
                                </a>
                            </li> --}}


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

                            <li class="nk-menu-item">
                                <a href="/truck/reports" class="nk-menu-link">
                                    <span class="nk-menu-text">Trucks Report </span>
                                </a>
                            </li>




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
                    </li>

                </ul>
            </div>
        </div>
    </div>
</div>
