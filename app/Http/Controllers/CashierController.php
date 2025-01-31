<?php

namespace App\Http\Controllers;

use App\Models\MasterStockModel;
use App\Models\TransactionModel;
use Illuminate\Http\Request;
use App\Models\ProductModel;
use App\Models\StockModel;
use App\Models\CustomerModel;
use Illuminate\Support\Facades\DB;

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
            'items.*.product_id' => 'required|exists:tbl_products,product_id',
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
                'kilos' => $item['kilos'],
                'price_per_kilo' => $item['price_per_kilo'],
                'total' => $item['kilos'] * $item['price_per_kilo']
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
            \Log::info('Received transaction data:', $request->all());

            $maxAttempts = 3;
            $attempt = 0;
            $validated = null;

            do {
                try {
                    if ($attempt > 0) {
                        $newReceiptId = $this->generateUniqueReceiptId();
                        $requestData = $request->all();
                        $requestData['receipt_id'] = $newReceiptId;
                        $request->replace($requestData);
                    }

                    $validated = $request->validate([
                        'customer_id' => 'required|exists:tbl_customers,CustomerID',
                        'service_type' => 'required|string',
                        'items' => 'required|array',
                        'items.*.product_id' => 'required|integer|exists:tbl_master_stock,product_id',
                        'items.*.kilos' => 'required|numeric|min:0.1',
                        'items.*.price_per_kilo' => 'required|numeric|min:0',
                        'items.*.total' => 'required|numeric|min:0',
                        'subtotal' => 'required|numeric|min:0',
                        'discount_percentage' => 'required|numeric|min:0|max:100',
                        'discount_amount' => 'required|numeric|min:0',
                        'total_amount' => 'required|numeric|min:0',
                        'receipt_id' => 'required|string|unique:tbl_transactions,receipt_id',
                        'status' => 'nullable|string',
                        'payment_type' => 'required|string|in:cash,debit,online',
                        'reference_number' => 'nullable|string|max:255'
                    ]);

                    break;

                } catch (\Illuminate\Validation\ValidationException $e) {
                    if (!isset($e->errors()['receipt_id']) || $attempt >= $maxAttempts - 1) {
                        return response()->json([
                            'success' => false,
                            'message' => 'Validation failed',
                            'errors' => $e->errors()
                        ], 422);
                    }
                    $attempt++;
                }
            } while ($attempt < $maxAttempts);

            DB::beginTransaction();

            // Update stock levels
            foreach ($validated['items'] as $item) {
                $masterStock = MasterStockModel::where('product_id', $item['product_id'])
                    ->where('price', $item['price_per_kilo'])
                    ->lockForUpdate()
                    ->first();

                if (!$masterStock) {
                    throw new \Exception("Stock not found for product ID: {$item['product_id']} at price ₱{$item['price_per_kilo']}");
                }

                if ($masterStock->total_all_kilos < $item['kilos']) {
                    throw new \Exception("Insufficient stock for price ₱{$item['price_per_kilo']}. Available: {$masterStock->total_all_kilos}, Required: {$item['kilos']}");
                }

                $masterStock->total_all_kilos -= $item['kilos'];

                if ($masterStock->total_all_kilos <= 0) {
                    $masterStock->delete();
                } else {
                    $masterStock->save();
                }
            }

            // Update customer balance if payment type is debit
            if ($validated['payment_type'] === 'debit') {
                $result = DB::table('tbl_customers')
                    ->where('CustomerID', $validated['customer_id'])
                    ->update([
                        'Balance' => DB::raw('Balance + ' . $validated['subtotal']),
                        'updated_at' => now()
                    ]);
            }
            $now = \Carbon\Carbon::now('Asia/Manila');

            $transaction = TransactionModel::create([
                'CustomerID' => $validated['customer_id'],
                'service_type' => $validated['service_type'],
                'subtotal' => $validated['subtotal'],
                'discount_percentage' => $validated['discount_percentage'],
                'discount_amount' => $validated['discount_amount'],
                'total_amount' => $validated['total_amount'],
                'receipt_id' => $validated['receipt_id'],
                'status' => $validated['service_type'] === 'deliver' ? 'Not Assigned' : null,
                'payment_type' => $validated['payment_type'],
                'date' => $now,
                'reference_number' => $request->reference_number,
                'updated_at' => $now,
                'created_at' => $now
            ]);

            foreach ($validated['items'] as $item) {
                $transaction->items()->create([
                    'product_id' => $item['product_id'],
                    'kilos' => $item['kilos'],
                    'price_per_kilo' => $item['price_per_kilo'],
                    'total' => $item['total']
                ]);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'transaction_id' => $transaction->id,
                'receipt_id' => $validated['receipt_id']
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


}