<?php

namespace App\Http\Controllers;

use App\Models\TransactionModel;
use App\Models\TransactionItemModel;
use App\Models\ProductModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class TransactionController extends Controller
{
    public function saveTransaction(Request $request)
    {
        try {
            DB::connection()->enableQueryLog();

            // Log the incoming request
            \Log::info('Transaction Request:', [
                'payment_type' => $request->payment_type,
                'all_data' => $request->all()
            ]);

            DB::beginTransaction();

            // Ensure payment_type is properly formatted
            $paymentType = strtolower(trim($request->payment_type));
            if (!in_array($paymentType, ['cash', 'debit'])) {
                $paymentType = 'cash'; // Default fallback
            }

            // Build transaction data
            $transactionData = [
                'date' => now(),
                'receipt_id' => $request->receipt_id,
                'CustomerID' => (int) $request->customer_id,
                'service_type' => $request->service_type,
                'subtotal' => (float) $request->subtotal,
                'discount_percentage' => (float) ($request->discount_percentage ?? 0),
                'discount_amount' => (float) ($request->discount_amount ?? 0),
                'total_amount' => (float) ($request->total_amount ?? 0),
                'status' => $request->service_type === 'deliver' ? 'Not Assigned' : null,
                'amount_paid' => (float) $request->amount_paid,
                'change_amount' => (float) $request->change_amount,
            ];

            // Insert initial transaction and get ID
            $transactionId = DB::table('tbl_transactions')->insertGetId($transactionData);

            // Update payment_type separately using direct SQL
            DB::statement('UPDATE tbl_transactions SET payment_type = ? WHERE transaction_id = ?', [$paymentType, $transactionId]);

            // Verify the payment type was saved correctly
            $savedTransaction = DB::select('SELECT payment_type FROM tbl_transactions WHERE transaction_id = ?', [$transactionId]);
            \Log::info('Saved payment type:', ['payment_type' => $savedTransaction[0]->payment_type ?? null]);

            // If payment type still doesn't match, try one more time with a prepared statement
            if (($savedTransaction[0]->payment_type ?? '') !== $paymentType) {
                DB::select('UPDATE tbl_transactions SET payment_type = :payment_type WHERE transaction_id = :id', [
                    'payment_type' => $paymentType,
                    'id' => $transactionId
                ]);
            }

            // Process items
            if (!empty($request->items)) {
                foreach ($request->items as $item) {
                    DB::table('tbl_transaction_items')->insert([
                        'transaction_id' => $transactionId,
                        'product_id' => (int) $item['product_id'],
                        'kilos' => (float) $item['kilos'],
                        'price_per_kilo' => (float) $item['price_per_kilo'],
                        'total' => (float) $item['total']
                    ]);
                }
            }

            DB::commit();

            // Final verification
            $finalTransaction = DB::select('SELECT * FROM tbl_transactions WHERE transaction_id = ?', [$transactionId]);
            \Log::info('Final transaction data:', ['transaction' => $finalTransaction[0]]);

            return response()->json([
                'success' => true,
                'message' => 'Transaction saved successfully',
                'transaction_id' => $transactionId,
                'debug' => [
                    'intended_payment_type' => $request->payment_type,
                    'processed_payment_type' => $paymentType,
                    'final_payment_type' => $finalTransaction[0]->payment_type ?? null,
                    'queries' => DB::getQueryLog()
                ]
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Transaction failed:', [
                'error' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString(),
                'request_data' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Transaction failed: ' . $e->getMessage()
            ], 500);
        }
    }
}
