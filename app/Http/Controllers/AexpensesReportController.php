<?php

namespace App\Http\Controllers;
use App\Models\ExpenseModel;
use Illuminate\Http\Request;

class AexpensesReportController extends Controller
{

    public function index(Request $request) {
        $query = ExpenseModel::query();

        // Apply date filter if provided
        if ($request->has('date')) {
            $query->whereDate('created_at', $request->date);
        }

        // Fetch the expenses with the optional date filter
        $expenses = $query->orderBy('created_at', 'desc')->get();

        return view('admin.pages.reports.expenses-report', compact('expenses'));
    }
}
