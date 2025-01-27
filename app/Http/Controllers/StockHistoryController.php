<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\StockHistoryModel;
use App\Models\StockModel;
use Illuminate\Http\Request;

class StockHistoryController extends Controller
{
    /**
     * Display the stock history.
     *
     * @return \Illuminate\View\View
     */
    public function showStockHistory()
    {


        $stocks = StockModel::with('stockHistory')->get();
        // dd($stocks->toArray());


        return view('admin.pages.history.stock-history', compact('stocks'));
    }

    /*
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $stock_id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updateStock(Request $request, $stock_id)
    {

        $validatedData = $request->validate([
            'stock_kilos' => 'required|numeric',
        ]);

        try {

            $stock = StockModel::findOrFail($stock_id);


            $stock->stock_kilos = $validatedData['kilos'];
            $stock->save();

            StockHistoryModel::create([
                'stock_id' => $stock->stock_id,
                'product_id' => $stock->product_id,
                'stock_kilos' => $stock->stock_kilos,
                'change_reason' => 'Stock updated manually',
                'created_at' => now(),
            ]);

            return redirect()->back()->with('success', 'Stock updated successfully!');
        } catch (\Exception $e) {
            \Log::error('Error updating stock: ' . $e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while updating the stock.');
        }
    }
}
