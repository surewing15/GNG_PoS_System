<?php

namespace App\Http\Controllers;

use App\Models\TransactionModel;
use Illuminate\Http\Request;

class AsaleReportController extends Controller
{
    public function index(Request $request)
    {
        // Initialize query for TransactionModel with eager loading
        $query = TransactionModel::with('customer')
            ->select('receipt_id', 'CustomerID', 'total_amount', 'subtotal', 'service_type', 'created_at');

        // Apply single date filter if provided
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Fetch transactions and order by created_at in descending order
        $transactions = $query->orderBy('created_at', 'desc')->get();

        // Calculate totals
        $totalAmount = $transactions->sum('total_amount');
        $totalSubtotal = $transactions->sum('subtotal');

        // Prepare data for chart (timestamps and amounts)
        $labels = $transactions->pluck('created_at')->map(function ($date) {
            return $date->format('M d'); // Format the date for better display
        });

        $totalAmountData = $transactions->pluck('total_amount');
        $subtotalData = $transactions->pluck('subtotal');

        // Pass the data and filter input to the view
        return view('admin.pages.reports.sale', [
            'transactions' => $transactions,
            'date' => $request->date, // Pass the selected date back to the view
            'totalAmount' => $totalAmount,
            'totalSubtotal' => $totalSubtotal,
            'labels' => $labels, // Labels for x-axis (dates)
            'totalAmountData' => $totalAmountData, // Data for total_amount
            'subtotalData' => $subtotalData, // Data for subtotal
        ]);
    }

}
