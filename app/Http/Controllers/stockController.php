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
            'cart.*.price' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            foreach ($request->input('cart') as $item) {
                $productId = $item['product_id'];
                $kilos = $item['kilos'];
                $price = $item['price'];
                $currentDate = now()->toDateString();

                // Check if a record exists with the same product_id, price, and date in StockModel
                $existingStock = StockModel::where('product_id', $productId)
                    ->where('price', $price)
                    ->whereDate('created_at', $currentDate)
                    ->first();

                if ($existingStock) {
                    // Update existing stock
                    $existingStock->stock_kilos += $kilos;
                    $existingStock->save();
                } else {
                    // Create a new stock entry
                    StockModel::create([
                        'product_id' => $productId,
                        'stock_kilos' => $kilos,
                        'price' => $price,
                    ]);
                }

                // Update the MasterStockModel
                $existingMasterStock = MasterStockModel::where('product_id', $productId)
                    ->where('price', $price)
                    ->whereDate('created_at', $currentDate)
                    ->first();

                if ($existingMasterStock) {
                    // Combine with existing MasterStock entry
                    $existingMasterStock->total_all_kilos += $kilos;
                    $existingMasterStock->save();
                } else {
                    // Create a new MasterStock entry for a different price or date
                    MasterStockModel::create([
                        'product_id' => $productId,
                        'total_all_kilos' => $kilos,
                        'price' => $price,
                        'created_at' => $currentDate,
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

