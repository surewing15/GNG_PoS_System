{{-- @extends('admin.theme.layout')
@section('content')
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">Stocks History</h4>
            </div>
        </div>
        <div class="card card-preview">
            <table class="table table-ulogs">
                <thead class="table-light">
                    <tr>
                        <th class="tb-col-sku"><span class="overline-title">Product Code (SKU)</span></th>
                        <th class="tb-col-quantity"><span class="overline-title">Quantity</span></th>
                        <th class="tb-col-date"><span class="overline-title">Date</span></th>
                        <th class="tb-col-time"><span class="overline-title">Time</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stocks as $stock)
                        @foreach ($stock->stockHistory as $history)
                            <tr>
                                <td class="tb-col-sku">{{ $stock->product->product_sku ?? 'N/A' }}</td>
                                <td class="tb-col-quantity"><span class="sub-text">{{ $stock->stock_quantity }}</span>
                                </td>
                                <td class="tb-col-date"><span
                                        class="sub-text">{{ $history->updated_at ? $history->updated_at->format('F j, Y') : 'N/A' }}</span>
                                </td>
                                <td class="tb-col-time"><span
                                        class="sub-text">{{ $history->updated_at ? $history->updated_at->format('H:i') : 'N/A' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach


                </tbody>
            </table>
        </div><!-- .card -->
    </div>
@endsection --}}

<x-app-layout>
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">Stocks History</h4>
            </div>
        </div>
        <div class="card card-preview">
            <table class="table table-ulogs">
                <thead class="table-light">
                    <tr>
                        <th class="tb-col-sku"><span class="overline-title">Product Code (SKU)</span></th>
                        <th class="tb-col-quantity"><span class="overline-title">Bags</span></th>
                        <th class="tb-col-quantity"><span class="overline-title">Kilos</span></th>
                        <th class="tb-col-date"><span class="overline-title">Date</span></th>
                        <th class="tb-col-time"><span class="overline-title">Time</span></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($stocks as $stock)
                        @foreach ($stock->stockHistory as $history)
                            <tr>
                                <td class="tb-col-sku">{{ $stock->product->product_sku ?? 'N/A' }}</td>
                                <td></td>
                                <td class="tb-col-quantity"><span class="sub-text">{{ $stock->stock_quantity }}</span>
                                </td>

                                <td class="tb-col-date"><span
                                        class="sub-text">{{ $history->updated_at ? $history->updated_at->format('F j, Y') : 'N/A' }}</span>
                                </td>
                                <td class="tb-col-time"><span
                                        class="sub-text">{{ $history->updated_at ? $history->updated_at->format('H:i') : 'N/A' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    @endforeach


                </tbody>
            </table>
        </div><!-- .card -->
    </div>
</x-app-layout>
