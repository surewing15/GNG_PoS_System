<?php

namespace App\Http\Controllers;

use App\Models\StockModel;
use Illuminate\Http\Request;

class InventoryReportController extends Controller
{
    public function index(Request $request)
    {
        // Initialize query for StockModel with eager loading
        $query = StockModel::with('product');

        // Apply date filter if provided
        if ($request->has('date')) {
            $query->whereDate('updated_at', $request->date);
        }

        // Fetch the filtered or full dataset
        $stocks = $query->orderBy('updated_at', 'desc')->get();

        // Pass data and filter input to the view
        return view('admin.pages.reports.inventory', [
            'stocks' => $stocks,
            'date' => $request->date, // Pass the selected date back to the view
        ]);
    }
}
