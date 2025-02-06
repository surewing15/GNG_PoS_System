<?php

namespace App\Http\Controllers;

use App\Models\TransactionModel;
use App\Services\OrderPrinterService;
use App\Services\ThermalPrinterService;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function print($transactionId)
    {
        try {
            $transaction = TransactionModel::with(['customer', 'items.product'])
                ->findOrFail($transactionId);

            $printerService = new OrderPrinterService();
            $printerService->printReceipt($transaction);

            return response()->json([
                'success' => true,
                'message' => 'Receipt printed successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to print receipt: ' . $e->getMessage()
            ], 500);
        }
    }
}
