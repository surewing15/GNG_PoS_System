{{-- @extends('cashier.theme.layout')
@section('content')
<div class="nk-content-inner">
    <div class="nk-content-body">
        <div class="nk-block-head nk-block-head-sm">
            <div class="nk-block-between">
                <div class="nk-block-head-content">
                    <h3 class="nk-block-title page-title">Orders</h3>
                </div><!-- .nk-block-head-content -->
                <div class="nk-block-head-content">
                    <div class="toggle-wrap nk-block-tools-toggle">
                        <a href="#" class="btn btn-icon btn-trigger toggle-expand me-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                        <div class="toggle-expand-content" data-content="pageMenu">
                            <ul class="nk-block-tools g-3">
                                <li>
                                    <div class="form-control-wrap">
                                        <div class="form-icon form-icon-right">
                                            <em class="icon ni ni-search"></em>
                                        </div>
                                        <input type="text" class="form-control" id="default-04" placeholder="Quick search by id">
                                    </div>
                                </li>
                                <li>
                                    <div class="drodown">
                                        <a href="#" class="dropdown-toggle dropdown-indicator btn btn-outline-light btn-white" data-bs-toggle="dropdown">Status</a>
                                        <div class="dropdown-menu dropdown-menu-end">
                                            <ul class="link-list-opt no-bdr">
                                                <li><a href="#"><span>On Hold</span></a></li>
                                                <li><a href="#"><span>Delivered</span></a></li>
                                                <li><a href="#"><span>Rejected</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                                <li class="nk-block-tools-opt">
                                    <a href="#" class="btn btn-icon btn-primary d-md-none"><em class="icon ni ni-plus"></em></a>
                                    <a href="#" class="btn btn-primary d-none d-md-inline-flex"><em class="icon ni ni-plus"></em><span>Add Order</span></a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div><!-- .nk-block-head-content -->
            </div><!-- .nk-block-between -->
        </div><!-- .nk-block-head -->
        <div class="nk-block">
            <div class="nk-tb-list is-separate is-medium mb-3">
                <div class="nk-tb-item nk-tb-head">
                    <div class="nk-tb-col nk-tb-col-check">
                        <div class="custom-control custom-control-sm custom-checkbox notext">
                            <input type="checkbox" class="custom-control-input" id="oid">
                            <label class="custom-control-label" for="oid"></label>
                        </div>
                    </div>
                    <div class="nk-tb-col"><span>Item (SKU)</span></div>
                    <div class="nk-tb-col tb-col-md"><span>Date</span></div>
                    <div class="nk-tb-col"><span class="d-none d-sm-block">Status</span></div>
                    <div class="nk-tb-col tb-col-sm"><span>Customer</span></div>
                    <div class="nk-tb-col tb-col-md"><span>Purchased</span></div>
                    <div class="nk-tb-col"><span>Total</span></div>
                    <div class="nk-tb-col nk-tb-col-tools">
                        <ul class="nk-tb-actions gx-1 my-n1">
                            <li>
                                <div class="drodown">
                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger me-n1" data-bs-toggle="dropdown" aria-expanded="false"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-end" style="">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="#"><em class="icon ni ni-edit"></em><span>Update Status</span></a></li>
                                            <li><a href="#"><em class="icon ni ni-truck"></em><span>Mark as Delivered</span></a></li>
                                            <li><a href="#"><em class="icon ni ni-money"></em><span>Mark as Paid</span></a></li>
                                            <li><a href="#"><em class="icon ni ni-report-profit"></em><span>Send Invoice</span></a></li>
                                            <li><a href="#"><em class="icon ni ni-trash"></em><span>Remove Orders</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div><!-- .nk-tb-item -->
                <div class="nk-tb-item">
                    <div class="nk-tb-col nk-tb-col-check">
                        <div class="custom-control custom-control-sm custom-checkbox notext">
                            <input type="checkbox" class="custom-control-input" id="oid10">
                            <label class="custom-control-label" for="oid10"></label>
                        </div>
                    </div>
                    <div class="nk-tb-col">
                        <span class="tb-lead"><a href="#">#95959</a></span>
                    </div>
                    <div class="nk-tb-col tb-col-md">
                        <span class="tb-sub">May 23, 2020</span>
                    </div>
                    <div class="nk-tb-col">
                        <span class="dot bg-success d-sm-none"></span>
                        <span class="badge badge-sm badge-dot has-bg bg-success d-none d-sm-inline-flex">Paid</span>
                    </div>
                    <div class="nk-tb-col tb-col-sm">
                        <span class="tb-sub">Jane Harris</span>
                    </div>
                    <div class="nk-tb-col tb-col-md">
                        <span class="tb-sub text-primary">Waterproof Speaker</span>
                    </div>
                    <div class="nk-tb-col">
                        <span class="tb-lead">$ 99.49</span>
                    </div>
                    <div class="nk-tb-col nk-tb-col-tools">
                        <ul class="nk-tb-actions gx-1">
                            <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" title="" data-bs-original-title="Mark as Delivered">
                                    <em class="icon ni ni-truck"></em></a></li>
                            <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" title="" data-bs-original-title="View Order">
                                    <em class="icon ni ni-eye"></em></a></li>
                            <li>
                            </li><li>
                                <div class="drodown me-n1">
                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="#"><em class="icon ni ni-eye"></em><span>Order Details</span></a></li>
                                            <li><a href="#"><em class="icon ni ni-money"></em><span>Mark as Paid</span></a></li>
                                            <li><a href="#"><em class="icon ni ni-report-profit"></em><span>Send Invoice</span></a></li>
                                            <li><a href="#"><em class="icon ni ni-trash"></em><span>Delete Order</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div><!-- .nk-tb-item -->

                <div class="nk-tb-item">
                    <div class="nk-tb-col nk-tb-col-check">
                        <div class="custom-control custom-control-sm custom-checkbox notext">
                            <input type="checkbox" class="custom-control-input" id="oid10">
                            <label class="custom-control-label" for="oid10"></label>
                        </div>
                    </div>
                    <div class="nk-tb-col">
                        <span class="tb-lead"><a href="#">#95959</a></span>
                    </div>
                    <div class="nk-tb-col tb-col-md">
                        <span class="tb-sub">May 23, 2020</span>
                    </div>
                    <div class="nk-tb-col">
                        <span class="dot bg-success d-sm-none"></span>
                        <span class="badge badge-sm badge-dot has-bg bg-danger d-none d-sm-inline-flex">Not Paid</span>
                    </div>
                    <div class="nk-tb-col tb-col-sm">
                        <span class="tb-sub">Jane Harris</span>
                    </div>
                    <div class="nk-tb-col tb-col-md">
                        <span class="tb-sub text-primary">Waterproof Speaker</span>
                    </div>
                    <div class="nk-tb-col">
                        <span class="tb-lead">$ 99.49</span>
                    </div>
                    <div class="nk-tb-col nk-tb-col-tools">
                        <ul class="nk-tb-actions gx-1">
                            <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" title="" data-bs-original-title="Mark as Delivered">
                                    <em class="icon ni ni-truck"></em></a></li>
                            <li class="nk-tb-action-hidden"><a href="#" class="btn btn-icon btn-trigger btn-tooltip" title="" data-bs-original-title="View Order">
                                    <em class="icon ni ni-eye"></em></a></li>
                            <li>
                            </li><li>
                                <div class="drodown me-n1">
                                    <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-bs-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                    <div class="dropdown-menu dropdown-menu-end">
                                        <ul class="link-list-opt no-bdr">
                                            <li><a href="#"><em class="icon ni ni-eye"></em><span>Order Details</span></a></li>
                                            <li><a href="#"><em class="icon ni ni-money"></em><span>Mark as Paid</span></a></li>
                                            <li><a href="#"><em class="icon ni ni-report-profit"></em><span>Send Invoice</span></a></li>
                                            <li><a href="#"><em class="icon ni ni-trash"></em><span>Delete Order</span></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div><!-- .nk-tb-item -->
            </div><!-- .nk-tb-list -->

            <div class="card">
                <div class="card-inner">
                    <div class="nk-block-between-md g-3">
                        <div class="g">
                            <ul class="pagination justify-content-center justify-content-md-start">
                                <li class="page-item"><a class="page-link" href="#"><em class="icon ni ni-chevrons-left"></em></a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><span class="page-link"><em class="icon ni ni-more-h"></em></span></li>
                                <li class="page-item"><a class="page-link" href="#">6</a></li>
                                <li class="page-item"><a class="page-link" href="#">7</a></li>
                                <li class="page-item"><a class="page-link" href="#"><em class="icon ni ni-chevrons-right"></em></a></li>
                            </ul><!-- .pagination -->
                        </div>
                        <div class="g">
                            <div class="pagination-goto d-flex justify-content-center justify-content-md-start gx-3">
                                <div>Page</div>
                                <div>
                                    <select class="form-select js-select2 select2-hidden-accessible" data-search="on" data-dropdown="xs center" data-select2-id="1" tabindex="-1" aria-hidden="true">
                                        <option value="page-1" data-select2-id="3">1</option>
                                        <option value="page-2">2</option>
                                        <option value="page-4">4</option>
                                        <option value="page-5">5</option>
                                        <option value="page-6">6</option>
                                        <option value="page-7">7</option>
                                        <option value="page-8">8</option>
                                        <option value="page-9">9</option>
                                        <option value="page-10">10</option>
                                        <option value="page-11">11</option>
                                        <option value="page-12">12</option>
                                        <option value="page-13">13</option>
                                        <option value="page-14">14</option>
                                        <option value="page-15">15</option>
                                        <option value="page-16">16</option>
                                        <option value="page-17">17</option>
                                        <option value="page-18">18</option>
                                        <option value="page-19">19</option>
                                        <option value="page-20">20</option>
                                    </select>
                                </div>
                                <div>OF 102</div>
                            </div>
                        </div><!-- .pagination-goto -->
                    </div><!-- .nk-block-between -->
                </div>
            </div>
        </div><!-- .nk-block -->
    </div>
</div>
@endsection --}}
