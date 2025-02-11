<x-app-layout>
    <div class="nk-block nk-block-lg">
        <div class="nk-block-head">
            <div class="nk-block-head-content">
                <h4 class="nk-block-title">Stocks History Export</h4>
            </div>
        </div>
        <div class="card card-bordered card-preview">
            <div class="card-inner">
                <table class="datatable-init-export nowrap table" data-export-title="Export">
                    <thead>
                        <tr>
                            <th class="tb-col-sku"><span class="overline-title">Product Code (SKU)</span></th>
                            <th class="tb-col-quantity"><span class="overline-title">Kilos</span></th>
                            <th class="tb-col-date"><span class="overline-title">Date</span></th>
                            <th class="tb-col-time"><span class="overline-title">Time</span></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($stocks as $stock)
                            <tr>
                                <td class="tb-col-sku">{{ $stock->product->product_sku ?? 'N/A' }}</td>
                                <td class="tb-col-quantity">
                                    <span class="sub-text">{{ $stock->stock_kilos }} kilos</span>
                                </td>
                                <td class="tb-col-date">
                                    <span
                                        class="sub-text">{{ $stock->created_at ? $stock->created_at->format('F j, Y') : 'N/A' }}</span>
                                </td>
                                <td class="tb-col-time">
                                    <span
                                        class="sub-text">{{ $stock->created_at ? $stock->created_at->format('H:i:s') : 'N/A' }}</span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
