<?php

namespace App\Http\Controllers;

use App\Models\MasterStockModel;
use App\Models\TransactionModel;
use App\Services\OrderPrinterService;
use Illuminate\Http\Request;
use App\Models\ProductModel;
use App\Models\StockModel;
use Illuminate\Support\Facades\Auth;
use App\Models\CustomerModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Exception;

class CashierController extends Controller
{
    public function index(Request $request)
    {
        $stocks = StockModel::with('product')->get();
        $MasterStocks = MasterStockModel::all();
        $customers = CustomerModel::all();
        // dd($customers);
        // dd($customers->pluck('CustomerID'));

        return view('cashier.pages.pos.pos', compact('stocks', 'customers', 'MasterStocks'));
    }

    public function searchSku($sku)
    {
        $sku = trim($sku);

        $masterStocks = MasterStockModel::whereHas('product', function ($query) use ($sku) {
            $query->where('product_sku', 'like', '%' . $sku . '%');
        })
            ->with('product')
            ->get();

        if ($masterStocks->isNotEmpty()) {
            // Group stocks by price and sum their kilos
            $groupedResults = $masterStocks->groupBy('price')->map(function ($stocks) {
                $firstStock = $stocks->first();
                return [
                    'product_id' => $firstStock->product->product_id,
                    'product_sku' => $firstStock->product->product_sku,
                    'product_name' => $firstStock->product->product_name ?? '',
                    'category' => $firstStock->product->category ?? '',
                    'description' => $firstStock->product->p_description ?? '',
                    'price' => $firstStock->price ?? 0,
                    'stock_kilos' => $stocks->sum('total_all_kilos'),
                    'dr' => $firstStock->dr ?? '',
                    'created_at' => $firstStock->created_at,
                    'img' => $firstStock->product->img ?? '',
                ];
            })->values();

            $filteredResults = $groupedResults->filter(function ($product) {
                return $product['stock_kilos'] > 0;
            });

            // Sort the results to prioritize ₱7.00 price for USB
            $sortedResults = $filteredResults->sortBy(function ($product) {
                // If it's a USB product with ₱7.00 price, give it highest priority
                if (strcasecmp($product['product_sku'], 'USB') === 0 && $product['price'] == 7.00) {
                    return 0;
                }
                // For all other products, sort by created_at in descending order
                return 1;
            })->values();

            return response()->json([
                'success' => true,
                'products' => $sortedResults
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'No products found for this SKU.'
        ]);
    }
    public function searchCustomers($query)
    {
        try {
            $query = trim($query);

            \Log::info('Searching customers with query: ' . $query); // Add logging

            $customers = CustomerModel::where(function ($q) use ($query) {
                $q->where('FirstName', 'like', '%' . $query . '%')
                    ->orWhere('LastName', 'like', '%' . $query . '%')
                    ->orWhere('Address', 'like', '%' . $query . '%');
            })
                ->limit(10) // Limit results for better performance
                ->get();

            \Log::info('Found customers:', $customers->toArray()); // Add logging

            $formattedCustomers = $customers->map(function ($customer) {
                return [
                    'id' => $customer->CustomerID,
                    'name' => $customer->FirstName . ' ' . $customer->LastName,
                    'phone' => $customer->Address ?? 'No phone'
                ];
            });

            return response()->json([
                'success' => true,
                'customers' => $formattedCustomers
            ]);

        } catch (\Exception $e) {
            \Log::error('Customer search error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error searching customers: ' . $e->getMessage()
            ], 500);
        }
    }
    public function generateReceipt(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'required|string',
            'phone' => 'required|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:tbl_product,product_id',  // Updated table name
            'items.*.kilos' => 'required|numeric',
            'items.*.price_per_kilo' => 'required|numeric',
            'total_amount' => 'required|numeric'
        ]);

        $transaction = TransactionModel::create([
            'customer_name' => $validated['customer_name'],
            'phone' => $validated['phone'],
            'total_amount' => $validated['total_amount'],
            'receipt_id' => strtoupper(uniqid()),
            'date' => now()
        ]);

        foreach ($validated['items'] as $item) {
            $transaction->items()->create([
                'product_id' => $item['product_id'],
                'user_id' => Auth::id(),
                'kilos' => $item['kilos'],
                'head' => $item['head'] ?? 0,
                'dr' => $item['dr'] ?? '',
                'price_per_kilo' => $item['price_per_kilo'],
                'total' => $item['total']
            ]);
        }

        $transaction->load('items.product');
        $receiptHtml = view('cashier.modal.cashier-modal', compact('transaction'))->render();

        return response()->json([
            'success' => true,
            'receiptHtml' => $receiptHtml
        ]);
    }

    public function getReceiptData($receiptId)
    {
        $transaction = TransactionModel::with('items.product')->where('receipt_id', $receiptId)->firstOrFail();
        return view('cashier.modal.cashier-modal', compact('transaction'));
    }
    private function generateUniqueReceiptId()
    {
        do {
            // Generate a receipt ID
            $timestamp = now()->timestamp;
            $random = strtoupper(substr(uniqid(), -5));
            $receiptId = "RCP-{$random}";

            // Check if it exists
            $exists = TransactionModel::where('receipt_id', $receiptId)->exists();
        } while ($exists);

        return $receiptId;
    }

    public function getReceiptId()
    {
        $receiptId = $this->generateUniqueReceiptId();
        return response()->json(['receipt_id' => $receiptId]);
    }



public function saveTransaction(Request $request)
{
    try {
        DB::beginTransaction();

        $baseValidation = [
            'customer_id' => 'required|exists:tbl_customers,CustomerID',
            'service_type' => 'required|string',
            'payment_type' => 'required|string',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:tbl_product,product_id',
            'items.*.kilos' => 'required|numeric|min:0',
            'items.*.head' => 'nullable|integer|min:0',
            'items.*.dr' => 'nullable|string',
            'items.*.price_per_kilo' => 'required|numeric|min:0',
            'items.*.total' => 'required|numeric|min:0',
            'total_amount' => 'required|numeric|min:0',
            'subtotal' => 'required|numeric|min:0',
            'discount_percentage' => 'required|numeric|min:0',
            'discount_amount' => 'required|numeric|min:0'
        ];

        // Add conditional validation rules based on payment type
        if ($request->payment_type === 'online') {
            $baseValidation['reference_number'] = 'required|string';
            $baseValidation['amount_paid'] = 'required|numeric|min:0|same:total_amount';
        } else {
            $baseValidation['amount_paid'] = 'required|numeric|min:0';
            $baseValidation['advance_payment_used'] = 'nullable|numeric|min:0';
            $baseValidation['credit_charge'] = 'nullable|numeric|min:0';
        }

        $validated = $request->validate($baseValidation);

        // Get customer with lock for update
        $customer = CustomerModel::lockForUpdate()->find($validated['customer_id']);
        if (!$customer) {
            throw new \Exception('Customer not found');
        }

        // Initialize payment variables
        $advancePaymentUsed = 0;
        $cashPayment = 0;
        $creditCharge = 0;
        $referenceNumber = null;

        // Handle different payment types
        switch ($validated['payment_type']) {
            case 'online':
                $cashPayment = $validated['total_amount']; // Full amount for online payments
                $creditCharge = 0; // No credit charge for online payments
                $referenceNumber = $validated['reference_number'];
                break;

            case 'advance_payment':
                // Existing advance payment logic...
                break;

            default:
                // Existing logic for other payment types...
                break;
        }

        // Update customer balance only if there's a credit charge
        if ($creditCharge > 0) {
            $customer->Balance += $creditCharge;
            $customer->save();
        }

        // Create transaction record
        $transaction = TransactionModel::create([
            'CustomerID' => $validated['customer_id'],
            'user_id' => Auth::id(),
            'service_type' => $validated['service_type'],
            'payment_type' => $validated['payment_type'],
            'reference_number' => $referenceNumber,
            'subtotal' => $validated['subtotal'],
            'discount_percentage' => $validated['discount_percentage'],
            'discount_amount' => $validated['discount_amount'],
            'total_amount' => $validated['total_amount'],
            'amount_paid' => $cashPayment,
            'advance_payment_used' => $advancePaymentUsed,
            'credit_charge' => $creditCharge,
            'change_amount' => max(0, ($cashPayment + $advancePaymentUsed) - $validated['total_amount']),
            'receipt_id' => $request->receipt_id,
            'status' => $validated['service_type'] === 'deliver' ? 'Not Assigned' : null,
            'date' => now()->setTimezone('Asia/Manila'),
            'created_at' => now()->setTimezone('Asia/Manila'),
            'updated_at' => now()->setTimezone('Asia/Manila')
        ]);

        // Create transaction items and update stock
        foreach ($validated['items'] as $item) {
            $transaction->items()->create([
                'product_id' => $item['product_id'],
                'user_id' => Auth::id(),
                'kilos' => $item['kilos'],
                'head' => $item['head'] ?? 0,
                'dr' => $item['dr'] ?? '',
                'price_per_kilo' => $item['price_per_kilo'],
                'total' => $item['total']
            ]);

            // Update stock levels
            $masterStock = MasterStockModel::where('product_id', $item['product_id'])
                ->lockForUpdate()
                ->first();

            if (!$masterStock) {
                throw new \Exception("Stock not found for product ID: {$item['product_id']}");
            }

            if ($masterStock->total_all_kilos < $item['kilos']) {
                throw new \Exception("Insufficient kilos in stock for product ID: {$item['product_id']}");
            }

            $masterStock->total_all_kilos -= $item['kilos'];
            if (isset($item['head']) && $item['head'] > 0) {
                if ($masterStock->total_head < $item['head']) {
                    throw new \Exception("Insufficient head count in stock for product ID: {$item['product_id']}");
                }
                $masterStock->total_head -= $item['head'];
            }

            $masterStock->save();
        }

        DB::commit();

        return response()->json([
            'success' => true,
            'transaction_id' => $transaction->id,
            'message' => 'Transaction completed successfully'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        \Log::error('Transaction error:', [
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);

        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}
    public function cleanupEmptyStocks()
    {
        try {
            DB::beginTransaction();

            // Delete all records where both quantities are effectively zero
            $deleted = MasterStockModel::where(function ($query) {
                $query->where('total_all_kilos', '<=', 0.01)
                    ->where('total_head', '<=', 0);
            })->delete();

            DB::commit();

            \Log::info('Cleaned up empty stock records:', [
                'records_deleted' => $deleted
            ]);

            return response()->json([
                'success' => true,
                'records_deleted' => $deleted
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Cleanup error:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function updateStock(Request $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->items as $item) {
                $masterStock = MasterStockModel::where('product_id', $item['product_id'])->first();

                if (!$masterStock) {
                    throw new \Exception("Stock not found for product ID: {$item['product_id']}");
                }

                if ($masterStock->total_all_kilos < $item['kilos']) {
                    throw new \Exception("Insufficient stock for product ID: {$item['product_id']}");
                }

                $masterStock->total_all_kilos -= $item['kilos'];
                $masterStock->save();
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function order()
    {
        return view('cashier.pages.pos.order');
    }
    public function collection()
    {
        // Add any data you want to pass to the view
        $data = [
            'title' => 'Collection Page',
        ];

        return view('cashier.pages.collection.index', $data);
    }
    public function processAdvancePayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:tbl_customers,CustomerID',
                'amount' => 'required|numeric|min:0',
            ]);

            DB::beginTransaction();

            $customer = CustomerModel::lockForUpdate()->find($validated['customer_id']);
            if (!$customer) {
                throw new \Exception('Customer not found');
            }

            // Update customer with advance payment and user_id
            $customer->advance_payment += $validated['amount'];
            $customer->user_id = Auth::id();  // Add this to track who processed the payment
            $customer->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Advance payment recorded successfully',
                'new_balance' => $customer->advance_payment
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function getCustomerBalanceInfo($id)
    {
        $customer = CustomerModel::select('Balance', 'advance_payment')
            ->where('CustomerID', $id)
            ->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'balance' => (float) $customer->Balance,
            'advance_payment' => (float) $customer->advance_payment
        ]);
    }
    // CashierController.php
    public function printReceipt(Request $request)
    {
        try {
            $validated = $request->validate([
                'receipt_id' => 'required|string',
                'customer_name' => 'required|string',
                'service_type' => 'required|string',
                'payment_type' => 'required|string',
                'items' => 'required|array',
                'items.*.sku' => 'required|string',
                'items.*.kilos' => 'required|numeric',
                'items.*.price_per_kilo' => 'required|numeric',
                'items.*.total' => 'required|numeric',
                'subtotal' => 'required|numeric',
                'discount_amount' => 'required|numeric',
                'total_amount' => 'required|numeric',
                'amount_paid' => 'nullable|numeric',
                'change_amount' => 'nullable|numeric',
                'used_advance_payment' => 'nullable|numeric',
                'reference_number' => 'nullable|string'
            ]);

            // Create printer data array with all required fields
            $printerData = [
                'receipt_id' => $validated['receipt_id'],
                'customer_name' => $validated['customer_name'],
                'service_type' => $validated['service_type'],
                'payment_type' => $validated['payment_type'],
                'items' => $validated['items'],
                'subtotal' => $validated['subtotal'],
                'discount_amount' => $validated['discount_amount'],
                'total_amount' => $validated['total_amount'],
            ];

            // Add conditional payment details based on payment type
            switch ($validated['payment_type']) {
                case 'cash':
                    $printerData['amount_paid'] = $validated['amount_paid'];
                    $printerData['change_amount'] = $validated['change_amount'];
                    break;
                case 'advance_payment':
                    $printerData['used_advance_payment'] = $validated['used_advance_payment'];
                    break;
                case 'online':
                    $printerData['reference_number'] = $validated['reference_number'];
                    break;
            }

            $printerService = new OrderPrinterService();
            $printerService->printReceipt($printerData);

            return response()->json(['success' => true]);

        } catch (Exception $e) {
            \Log::error('Print error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

}