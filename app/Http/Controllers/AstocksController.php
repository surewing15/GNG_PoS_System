<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

use App\Models\StockModel;
use App\Models\ProductModel;
use App\Models\StockHistoryModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;




class AstocksController extends Controller
{
    /**
     * Display a listing of the stocks.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {

        $stocks = StockModel::with('product')
            ->select('product_id', DB::raw('SUM(stock_kilos) as total_kilos'))
            ->groupBy('product_id')
            ->get();

        $products = ProductModel::all();
        // dd($products);
        return view('admin.pages.stocks.index', compact('stocks', 'products'));

    }



    /**
     * Store a newly created stock in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|exists:tbl_product,product_id',
            'stock_kilos' => 'required|numeric', // allows decimal values
        ]);

        try {
            $product = ProductModel::where('product_id', $validatedData['product_id'])->first();

            $stock = StockModel::create([
                'product_id' => $validatedData['product_id'],
                'stock_kilos' => $validatedData['stock_kilos'],
            ]);

            $this->forwardToHistory($stock);

            return redirect()->back()->with('success', 'Stock information saved successfully.');
        } catch (\Exception $e) {
            \Log::error('Error saving stock information: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while saving the stock information.');
        }
    }


    /**
     *
     *
     * @param  \App\Models\StockModel  $stock
     * @return void
     */
    private function forwardToHistory($stock)
    {
        try {
            StockHistoryModel::create([
                'stock_id' => $stock->stock_id,
                'product_id' => $stock->product_id,
                'stock_kilos' => $stock->stock_kilos,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            \Log::error('Error saving to stock history: ' . $e->getMessage());
        }
    }
}
