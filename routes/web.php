<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AcustomerController;
use App\Http\Controllers\AorderController;
use App\Http\Controllers\AproductController;
use App\Http\Controllers\AstocksController;
use App\Http\Controllers\stockController;
use App\Http\Controllers\AexpensesController;
use App\Http\Controllers\StockHistoryController;
use App\Http\Controllers\CashierController;
use App\Http\Controllers\ApurchaseHistoryController;
use App\Http\Controllers\InventoryReportController;
use App\Http\Controllers\AsaleReportController;
use App\Http\Controllers\DenominationReportController;
use App\Http\Controllers\CTruckingController;
use App\Http\Controllers\CTruckingInvoiceController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\AdriverController;
use App\Http\Controllers\AhelperController;
use App\Http\Controllers\AtruckController;
use App\Http\Controllers\AexpensesReportController;
use App\Http\Controllers\TransactionController;
// use App\Http\Controllers\CashierRecieptController;
use App\Http\Controllers\CcustomerController;
use App\Http\Controllers\ClerkStocksController;
use App\Http\Controllers\ExpiredProductsController;
use App\Http\Controllers\TruckReportController;
use App\Http\Controllers\DiscountCodeController;
use App\Http\Controllers\CondemnController;
use App\Http\Controllers\StockLogController;
use App\Http\Controllers\ExportController;
use App\Http\Controllers\GenerateReportController;
use App\Http\Controllers\CreportController;
use Illuminate\Support\Facades\DB;
Route::get('/', function () {
    return view('auth.login');
});



Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {


    Route::get('/dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('/admin/customer', [AcustomerController::class, 'index']);
    Route::get('/admin/edit', [AcustomerController::class, 'edit']);
    Route::get('/admin/order', [AorderController::class, 'index']);
    Route::get('/admin/product', [AproductController::class, 'index']);
    Route::get('/admin/stocks', [AstocksController::class, 'index']);

    Route::get('/admin/stocks', [AstocksController::class, 'index']);
    Route::get('/truck/reports', [TruckReportController::class, 'index']);
    Route::get('/discount/code', [DiscountCodeController::class, 'index']);


    Route::get('/inventory-report', [InventoryReportController::class, 'index'])->name('inventory.report');
    Route::get('/sales-report', [AsaleReportController::class, 'index'])->name('sales.report');
    Route::get('/admin/inventory/reports', [InventoryReportController::class, 'index']);
    Route::get('/admin/wholesale/reports', [AsaleReportController::class, 'index']);
    Route::get('/admin/denomination/reports', [DenominationReportController::class, 'index']);
    Route::get('/admin/expenses/reports', [AexpensesReportController::class, 'index'])->name('reports.index');
    Route::post('/reports', [GenerateReportController::class, 'store'])->name('reports.store');

    Route::get('/admin/generate/reports', [GenerateReportController::class, 'index']);
    Route::get('/reports/{date}/export', [GenerateReportController::class, 'export'])->name('reports.export');

    Route::get('/expenses-report', [AexpensesReportController::class, 'index'])->name('expenses.report');
    Route::get('/export/{type}', [ExportController::class, 'export'])->name('export');
    Route::get('/total-sales', [AdminController::class, 'getTotalSales']);
    Route::get('/sales-data', [AdminController::class, 'getSalesData']);


    // Route::get('/admin/stocks', [AstocksController::class, 'create']);
    Route::post('/admin/stocks', [AstocksController::class, 'store'])->name('stocks.store');

    // Route for displaying stock history
    Route::get('/admin/history', [StockHistoryController::class, 'showStockHistory'])->name('stock.history');
    Route::get('/admin/purchase/history', [ApurchaseHistoryController::class, 'index'])->name('stock.history');

    // Route for updating stock

    // Route::post('/update-stock/{stock_id}', [StockHistoryController::class, 'updateStock'])->name('stock.update');
    Route::get('/cashier/report/export', [CreportController::class, 'export'])->name('cashier.report.export');
    Route::get('/cashier/report', [CreportController::class, 'index']);
    Route::get('/cashier/expenses', [AexpensesController::class, 'index'])->name('expenses.index');
    Route::post('/cashier/expenses', [AexpensesController::class, 'store'])->name('expenses.store');
    Route::post('/expenses/{expense}/return', [AexpensesController::class, 'returnCash'])->name('expenses.return');

    Route::prefix('admin')->group(function () {
        Route::resource('driver', AdriverController::class);
    });
    Route::prefix('admin')->group(function () {
        Route::resource('helper', AhelperController::class);
    });

    Route::prefix('admin')->group(function () {
        Route::resource('truck', AtruckController::class);
        Route::post('truck/save', [AtruckController::class, 'store'])->name('truck.save');
    });


    // Route::get('/admin/order/history', function () {
    //     return view('admin.pages.history.order-history');
    // });

    // Route::get('/admin/history', function () {
    //     return view('admin.pages.history.stock-history');
    // });


    // Route::post('/stocks', [StockController::class, 'store'])->name('stocks.store');

    // Route::resource('stocks', stockController::class);
    // Route::post('/clerk/store', [stockController::class, 'store'])->name('stocks.store');

    // Route::get('/stocks', [stockController::class, 'index']);

    Route::get('/clerk/products', function () {
        return view('clerk.pages.products.index');
    });

    Route::get('/customer/dashboard', function () {
        return view('user.index');
    });

    Route::get('/customer/invoice', function () {
        return view('user.pages.invoice');
    });

    Route::post('/product', [AproductController::class, 'store'])->name('product.store');
    Route::resource('customers', AcustomerController::class);




    // Route to display the stocks page
    Route::get('/stocks', [StockController::class, 'index'])->name('stocks.index');
    Route::get('/condemn/stocks', [CondemnController::class, 'index']);
    // Route to handle the form submission for stocks
    Route::post('/stocks/store', [stockController::class, 'store'])->name('stocks.store');
    Route::post('/stocks/update-price/{master_stock_id}', [StockController::class, 'updatePrice'])->name('clerk.stocks.update-price');

    Route::get('/clerk/stocks', [stockController::class, 'stocklist']);
    Route::get('/logs/stocks', [StockLogController::class, 'index']);
    Route::get('/expired/products', [ExpiredProductsController::class, 'index']);
    Route::get('/search-sku/{query}', [CashierController::class, 'searchSku']);
    Route::get('/search-customers/{query}', [App\Http\Controllers\CashierController::class, 'searchCustomers'])->name('search.customers');

    // Route::get('/stocks', [AstocksController::class, 'index'])->name('stocks.index');
    // Route::get('/stocks/create', [AstocksController::class, 'create'])->name('stocks.create');
    // Route::post('admin/stocks', [AstocksController::class, 'store'])->name('stocks.store');

    // Route::resource('stocks', AstocksController::class);

    Route::prefix('cashier')->group(function () {
        Route::get('/pos', [CashierController::class, 'index'])->name('cashier.index');
        Route::get('/order', [CashierController::class, 'order']);
        Route::post('/add-to-cart', [CashierController::class, 'addToCart'])->name('cashier.addToCart');
        Route::post('/generate-receipt', [CashierController::class, 'generateReceipt'])->name('cashier.generate.receipt');
        Route::get('/receipt/{receiptId}', [CashierController::class, 'getReceiptData'])->name('cashier.receipt.show');
        Route::post('/sales/update-service-type', [SalesController::class, 'updateServiceType']);
        Route::get('/collection', [CashierController::class, 'collection'])->name('cashier.collection');
    });
    Route::post('/save-transaction', [CashierController::class, 'saveTransaction']);
    // Route::post('/save-transaction', [TransactionController::class, 'saveTransaction']);

    // Route::get('/test-payment-type', function() {
    //     try {
    //         DB::beginTransaction();

    //         $id = DB::table('tbl_transactions')->insertGetId([
    //             'date' => now(),
    //             'receipt_id' => 'TEST-'.rand(1000,9999),
    //             'CustomerID' => 1,
    //             'service_type' => 'walkin',
    //             'payment_type' => 'debit',
    //             'total_amount' => 100,
    //             'subtotal' => 100,
    //             'discount_percentage' => 20,
    //             'discount_amount' =>20,
    //             'amount_paid' =>20,
    //             'change_amount' => 20
    //         ]);

    //         $result = DB::table('tbl_transactions')
    //             ->where('transaction_id', $id)
    //             ->first();

    //         DB::rollBack();

    //         return response()->json([
    //             'inserted_id' => $id,
    //             'saved_data' => $result
    //         ]);
    //     } catch(\Exception $e) {
    //         DB::rollBack();
    //         return response()->json([
    //             'error' => $e->getMessage(),
    //             'trace' => $e->getTraceAsString()
    //         ], 500);
    //     }
    // });
    // CashierController routes
    Route::post('/cashier/update-cart-quantity', [CashierController::class, 'updateCartQuantity'])->name('cashier.updateCart');
    Route::post('/cashier/add-product', [CashierController::class, 'addProduct'])->name('cashier.addProduct');

    // CTruckingController routes with consistent cashier prefix
    // Route::get('/cashier/trucking', [CTruckingController::class, 'index'])->name('cashier.trucking.index');

    // Route::get('/delivery/{receipt_id}/details', [CTruckingController::class, 'getDeliveryDetails'])->name('delivery.details');
    // Route::post('/delivery/update-status', [CTruckingController::class, 'updateDeliveryStatus'])->name('delivery.update-status');

    // //////////////////////////////////

    // Route::prefix('cashier')->group(function () {
    //     Route::get('/trucking', [CTruckingController::class, 'index'])->name('trucking.index');
    //     Route::get('/trucking/create', [CTruckingController::class, 'create'])->name('trucking.create');
    //     Route::post('/trucking', [CTruckingController::class, 'store'])->name('trucking.store');
    // });


    Route::prefix('cashier')->group(function () {
        Route::get('/trucking', [CTruckingController::class, 'index'])->name('trucking.index');
        Route::get('/trucking/create', [CTruckingController::class, 'create'])->name('trucking.create');
        Route::post('/trucking', [CTruckingController::class, 'store'])->name('trucking.store');
    });

    // Delivery details routes
    // In web.php
    Route::get('/delivery/{receipt_id}/details', [CTruckingController::class, 'getDeliveryDetails'])
        ->name('delivery.details');

    Route::post('/delivery/update-status', [CTruckingController::class, 'updateDeliveryStatus'])->name('delivery.update-status');
    // Route::get('/cashier/trucking/create', [CTruckingController::class, 'Trucking'])->name('trucking.create');
    Route::get('/cashier/sales', [SalesController::class, 'index'])->name('sales.index');

    // CTruckingInvoiceController route
    Route::get('/cashier/invoice', [CTruckingInvoiceController::class, 'index'])->name('cashier.invoice.index');
    Route::get('/cashier/customer', [CcustomerController::class, 'index']);
    Route::post('/customers/save', [CcustomerController::class, 'store'])->name('customers.save');
    Route::get('/customer/collection/{id}', [CcustomerController::class, 'show']);
    Route::get('/customer/collection/{id}/details', [CcustomerController::class, 'getCollectionDetails']);
    Route::post('/customers/payment/store', [CcustomerController::class, 'storePayment'])->name('payments.storePayment');
    Route::get('/cashier/collection/{id}', [CcustomerController::class, 'getCollectionDetails']);
    Route::get('/cashier/collection/{id}', [CcustomerController::class, 'show'])->name('customers.show');

    // Route::get('/cashier/trucking/create', [CTruckingController::class, 'Trucking'])->name('trucking.create');
    // Route::post('/cashier/trucking/store', [CTruckingController::class, 'store'])->name('trucking.store');
    // Route::post('/trucking/store', [CTruckingController::class, 'store'])->name('trucking.store');


    // Route::get('/cart/receipt', [CashierRecieptController::class, 'index']);




    // Route::post('/cart/add', 'CashierController@addToCart')->name('cart.add');
    // Route::get('/cart', 'CashierController@showCart')->name('cart.show');

    Route::post('/cart/add', [CashierController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CashierController::class, 'showCart'])->name('cart.show');
    Route::post('/cart/reset', [CashierController::class, 'resetCart'])->name('cart.reset');
    Route::post('/cart/update', [CashierController::class, 'update'])->name('cart.update');


    // Route::prefix('cashier')->group(function () {
    //     Route::post('/transaction/add', [TransactionController::class, 'addTransaction'])->name('transaction.add');
    //     Route::post('/cart/add', [CashierController::class, 'addToCart'])->name('cart.add');
    //     Route::get('/cart/show', [CashierController::class, 'showCart'])->name('cart.show');
    //     Route::post('/cart/update', [CashierController::class, 'updateCart'])->name('cart.update');
    //     Route::post('/cart/reset', [CashierController::class, 'resetCart'])->name('cart.reset');
    // });
    // Route::post('/transactions', [TransactionController::class, 'store']);
    // Route::post('/transaction/store', [TransactionController::class, 'store'])->name('transaction.store');

    // Route::get('/cashier/wholesales/report ',[CashierReportController::class,'wholesale']);
    // Route::get('/cashier/denomination/report ',[CashierReportController::class,'denomination']);
    // Route::get('/cashier/eggsales/report ',[CashierReportController::class,'eggsales']);
    // Route::get('/cashier/retail/report ',[CashierReportController::class,'salesretail']);

    Route::get('/cashier/refresh-products', [CashierController::class, 'refreshProducts'])
        ->name('cashier.refresh-products');

    // In routes/web.php
    Route::post('/process-transaction', [CashierController::class, 'processTransaction'])->name('transaction.process');
    Route::post('/update-stock', [CashierController::class, 'updateStock']);
    Route::post('/cart/clear', [CashierController::class, 'clearCart'])->name('cart.clear');

});