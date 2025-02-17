<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ExpenseModel;
use Illuminate\Support\Facades\Auth;
class AexpensesController extends Controller
{
    public function index()
    {
        $expenses = ExpenseModel::all();
        return view('cashier.pages.expenses.index', compact('expenses'));
    }
    public function store(Request $request)
    {
        $request->validate([
            'withdraw_by' => 'nullable|string|max:255',
            'recieve_by' => 'nullable|string|max:255',
            'expenses_description' => 'required|string|max:255',
            'expenses_amount' => 'required|numeric|min:0',
        ]);

        ExpenseModel::create([
            'e_description' => $request->input('expenses_description'),
            'e_amount' => $request->input('expenses_amount'),
            'e_withdraw_by' => $request->input('withdraw_by') ?? null,
            'e_recieve_by' => $request->input('recieve_by') ?? null,
            'user_id' => Auth::id(),
        ]);

        return redirect()->back()->with('success', 'Expense created successfully!');
    }

    public function returnCash(Request $request, ExpenseModel $expense)
    {
        $request->validate([
            'return_amount' => 'required|numeric|min:0|max:' . $expense->e_amount,
            'return_by' => 'required|string|max:255',
            'return_description' => 'required|string|max:255',
        ]);

        $expense->update([
            'e_return_amount' => $request->return_amount,
            'e_return_by' => $request->return_by,
            'e_return_date' => now(),
            'e_return_description' => $request->return_description,
        ]);

        return redirect()->back()->with('success', 'Cash return recorded successfully!');
    }


}