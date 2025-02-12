<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StockModel;
use App\Models\ProductModel;
use App\Models\MasterStockModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
class stockController extends Controller
{
    public function index()
    {

        $products = ProductModel::all();


        return view('clerk.pages.stocks.index', compact('products'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'cart' => 'required|array',
            'cart.*.product_id' => 'required|exists:tbl_product,product_id',
            'cart.*.kilos' => 'required|numeric|min:0',
            'cart.*.head' => 'required|integer|min:0',
            'cart.*.price' => 'required|numeric|min:0',
            'dr' => 'required|string|max:50'
        ]);

        try {
            DB::beginTransaction();
            $drNumber = $request->input('dr');
            $currentDate = now();

            foreach ($request->input('cart') as $item) {
                // Create new stock entry
                StockModel::create([
                    'product_id' => $item['product_id'],
                    'stock_kilos' => $item['kilos'],
                    'head' => $item['head'],
                    'price' => $item['price'],
                    'dr' => $drNumber
                ]);

                // Check if master stock entry exists for this product and date
                $masterStock = MasterStockModel::where('product_id', $item['product_id'])
                    ->whereDate('created_at', $currentDate->toDateString())
                    ->first();

                if ($masterStock) {
                    // Update existing master stock
                    $masterStock->total_all_kilos += $item['kilos'];
                    $masterStock->total_head += $item['head'];
                    // Only update price if it's different
                    if ($masterStock->price != $item['price']) {
                        $masterStock->price = $item['price'];
                    }
                    $masterStock->dr = $drNumber; // Update DR
                    $masterStock->save();
                } else {
                    // Create new master stock entry
                    MasterStockModel::create([
                        'product_id' => $item['product_id'],
                        'total_all_kilos' => $item['kilos'],
                        'total_head' => $item['head'],
                        'price' => $item['price'],
                        'dr' => $drNumber
                    ]);
                }
            }

            DB::commit();
            return response()->json(['success' => true]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Stock save error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to save stock: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePrice(Request $request, $master_stock_id)
    {
        try {
            $validated = $request->validate([
                'product_price' => 'required|numeric|min:0',
            ]);

            $masterStock = MasterStockModel::findOrFail($master_stock_id);
            $masterStock->price = $request->product_price;
            $masterStock->save();

            return response()->json([
                'success' => true,
                'message' => 'Price updated successfully',
                'new_price' => $request->product_price
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update price: ' . $e->getMessage()
            ], 422);
        }
    }

    public function stocklist()
    {
        $masterStocks = MasterStockModel::with('product')->get();
        return view('clerk.pages.stocklist.index', compact('masterStocks'));
    }
}