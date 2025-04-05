<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransactionModel;


class SalesController extends Controller
{

    public function index()
    {
        $transactions = TransactionModel::with('customer')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($transaction) {
                return [
                    'receipt_id' => $transaction->receipt_id,
                    'customer' => $transaction->customer ?
                        $transaction->customer->FirstName . ' ' . $transaction->customer->LastName :
                        'sherwin aleronar',
                    'total_amount' => $transaction->subtotal ?? 0.00,
                    'created_at' => $transaction->created_at,
                    'service_type' => $transaction->service_type 
                ];
            });

        return view('cashier.pages.sales.index', compact('transactions'));
    }
    public function updateServiceType(Request $request)
    {
        try {
            $transaction = TransactionModel::where('receipt_id', $request->receipt_id)->first();

            if (!$transaction) {
                return response()->json([
                    'success' => false,
                    'message' => 'Transaction not found'
                ], 404);
            }

            $transaction->service_type = 'deliver';
            $transaction->save();

            return response()->json([
                'success' => true,
                'message' => 'Service type updated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating service type: ' . $e->getMessage()
            ], 500);
        }
    }

}
