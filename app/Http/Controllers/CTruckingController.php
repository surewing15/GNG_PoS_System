<?php

namespace App\Http\Controllers;

use App\Models\TruckingInfo;
use App\Models\DriverModel;
use App\Models\TruckModel;
use App\Models\HelperModel;
use App\Models\TransactionModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CTruckingController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status'); // Get the status filter from the query

        // Query all delivery transactions with relationships
        $deliverys = TransactionModel::where('service_type', 'deliver')
            ->with([
                'trucking.driver',     // Include driver details
                'trucking.helper',     // Include helper details
                'trucking.truck',      // Include truck details
                'customer'             // Include customer details
            ]);

        // Apply status filter only if a valid filter is selected
        if ($status && in_array($status, ['Not Assigned', 'On Going', 'Successful'])) {
            $deliverys->where('status', $status);
        }

        // Paginate results, ensuring ordering by latest records
        $deliverys = $deliverys->orderBy('created_at', 'desc')->paginate(10);

        // Ensure default status for records with invalid or null status
        $deliverys->each(function ($delivery) {
            if (!in_array($delivery->status, ['Not Assigned', 'On Going', 'Successful'])) {
                $delivery->status = 'Not Assigned';
                $delivery->save();
            }
        });

        // Log the delivery data for debugging purposes
        \Log::info('Delivery data:', ['deliverys' => $deliverys->toArray()]);

        // Return the appropriate view with deliveries
        return view('cashier.pages.trucking.index', compact('deliverys'));
    }


    public function create(Request $request)
    {


        $receiptId = $request->query('receipt_id');


        // Get transaction to fetch customer details
        $transaction = TransactionModel::where('receipt_id', $receiptId)->first();

         // Get transaction with customer details
         $transaction = DB::table('tbl_transactions')
         ->join('tbl_customers', 'tbl_transactions.CustomerID', '=', 'tbl_customers.CustomerID')
         ->where('tbl_transactions.receipt_id', $receiptId)
         ->select('tbl_transactions.*', 'tbl_customers.FirstName', 'tbl_customers.LastName')
         ->first();

     // Format the customer name
     $customerName = $transaction
         ? $transaction->FirstName . ' ' . $transaction->LastName
         : '';

     $customerId = $transaction ? $transaction->CustomerID : null;


        // Get only available drivers
        $drivers = DriverModel::whereNotIn('driver_id', function ($query) {
            $query->select('driver_id')
                ->from('trucking_info')
                ->join('tbl_transactions', 'trucking_info.receipt_no', '=', 'tbl_transactions.receipt_id')
                ->where('tbl_transactions.status', 'On Going');
        })->get();

        // Get only available trucks
        $trucks = TruckModel::where('truck_status', 'Available')
            ->whereNotIn('truck_id', function ($query) {
                $query->select('truck_id')
                    ->from('trucking_info')
                    ->join('tbl_transactions', 'trucking_info.receipt_no', '=', 'tbl_transactions.receipt_id')
                    ->where('tbl_transactions.status', 'On Going');
            })
            ->get();

        // Get only available helpers
        $helpers = HelperModel::whereNotIn('helper_id', function ($query) {
            $query->select('helper_id')
                ->from('trucking_info')
                ->join('tbl_transactions', 'trucking_info.receipt_no', '=', 'tbl_transactions.receipt_id')
                ->where('tbl_transactions.status', 'On Going');
        })->get();

        return view('cashier.pages.trucking.create', compact(
            'receiptId',
            'customerName',
            'customerId',
            'drivers',
            'trucks',
            'helpers'
        ));
    }

    public function store(Request $request)
    {
        \Log::info('Received form data:', $request->all());

        try {
            DB::beginTransaction();

            // Validate the request
            $validated = $request->validate([
                'receipt_no' => 'required',
                'driver_id' => 'required',
                'truck_id' => 'required',
                'helper_id' => 'required',
                'allowance' => 'required|numeric',
                'destination' => 'required',
                'CustomerID' => 'required'
            ]);

            // Check if the receipt_id already exists
            // $existingTransaction = TransactionModel::where('receipt_id', $validated['receipt_no'])->first();
            // if ($existingTransaction) {
            //     throw new \Exception('Duplicate receipt ID: ' . $validated['receipt_no']);
            // }

            // Check if truck is available
            $truck = TruckModel::find($validated['truck_id']);
            if (!$truck || $truck->truck_status !== 'Available') {
                throw new \Exception('Selected truck is not available');
            }

            // Check if driver is available
            $isDriverAvailable = !TruckingInfo::join('tbl_transactions', 'trucking_info.receipt_no', '=', 'tbl_transactions.receipt_id')
                ->where('driver_id', $validated['driver_id'])
                ->where('tbl_transactions.status', 'On Going')
                ->exists();

            if (!$isDriverAvailable) {
                throw new \Exception('Selected driver is not available');
            }

            // Check if helper is available
            $isHelperAvailable = !TruckingInfo::join('tbl_transactions', 'trucking_info.receipt_no', '=', 'tbl_transactions.receipt_id')
                ->where('helper_id', $validated['helper_id'])
                ->where('tbl_transactions.status', 'On Going')
                ->exists();

            if (!$isHelperAvailable) {
                throw new \Exception('Selected helper is not available');
            }


            // Find transaction
            $transaction = TransactionModel::where('receipt_id', $request->receipt_no)->first();
            if (!$transaction) {
                throw new \Exception('Transaction not found for receipt_no: ' . $request->receipt_no);
            }

            // Create trucking record
            $trucking = TruckingInfo::create([
                'receipt_no' => $validated['receipt_no'],
                    'driver_id' => $validated['driver_id'],
                    'truck_id' => $validated['truck_id'],
                    'helper_id' => $validated['helper_id'],
                    'allowance' => $validated['allowance'],
                    'destination' => $validated['destination'],
                    'CustomerID' => $validated['CustomerID'],
                'total_price' => $transaction->total_price ?? 0,
                'total_kilo' => $transaction->total_kilo ?? 0
            ]);

            // Update truck status to 'In Use'
            $truck->truck_status = 'In Use';
            $truck->save();

            // Update transaction status
            $transaction->status = 'On Going';
            $transaction->save();

            DB::commit();
            return redirect()->route('trucking.index')->with('success', 'Trucking information saved successfully');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Error: ' . $e->getMessage())->withInput();
        }
    }


    public function getDeliveryDetails($receipt_id)
    {
        try {
            \Log::info('Fetching delivery details', ['receipt_id' => $receipt_id]);

            // Get transaction with related data
            $transaction = TransactionModel::with([
                'trucking.driver',
                'trucking.truck'
            ])
                ->where('receipt_id', $receipt_id)
                ->first();

            \Log::info('Found transaction', ['transaction' => $transaction]);

            // Get customer details
            $customer = DB::table('tbl_customers')
                ->where('CustomerID', $transaction->CustomerID)
                ->first();

            $trucking = $transaction ? $transaction->trucking : null;

            // Get helper details from tbl_helper
            $helper = null;
            if ($trucking) {
                $helper = DB::table('tbl_helper')
                    ->where('helper_id', $trucking->helper_id)
                    ->first();
            }

            \Log::info('Trucking info', ['trucking' => $trucking]);

            // Prepare response with helper's full name
            $response = [
                'receipt_id' => $receipt_id,
                'customer_name' => $customer ?
                    $customer->FirstName . ' ' . $customer->LastName : 'N/A',
                'driver_name' => $trucking && $trucking->driver ?
                    $trucking->driver->fname . ' ' . $trucking->driver->lname : 'Not Assigned',
                'helper_name' => $helper ?
                    $helper->fname . ' ' . $helper->lname : 'Not Assigned',
                'truck_name' => $trucking && $trucking->truck ?
                    $trucking->truck->truck_name : 'Not Assigned',
                'allowance' => $trucking ? $trucking->allowance : 'N/A',
                'destination' => $trucking ? $trucking->destination : 'Not Set',
                'status' => $transaction ? $transaction->status : 'Not Assigned'
            ];

            \Log::info('Sending response', ['response' => $response]);
            return response()->json($response);

        } catch (\Exception $e) {
            \Log::error('Error in getDeliveryDetails', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'error' => 'Failed to load details: ' . $e->getMessage()
            ], 500);
        }
    }
    public function updateTruckStatus($truckId, $status)
    {
        try {
            $truck = TruckModel::findOrFail($truckId);
            $truck->truck_status = $status;
            $truck->save();

            return response()->json(['success' => true, 'message' => 'Truck status updated successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
    // In CTruckingController.php

    public function updateDeliveryStatus(Request $request)
    {
        \Log::info('Starting delivery status update', ['receipt_id' => $request->receipt_id]);

        try {
            DB::beginTransaction();

            if (!$request->receipt_id) {
                throw new \Exception('Receipt ID is required');
            }

            // Find the transaction with trucking info
            $transaction = TransactionModel::with('trucking')->where('receipt_id', $request->receipt_id)->first();

            if (!$transaction) {
                throw new \Exception('Transaction not found');
            }

            // Update transaction status to "Successful"
            $transaction->status = 'Successful';
            $transaction->save();

            // Update truck status if exists
            if ($transaction->trucking && $transaction->trucking->truck_id) {
                $truck = TruckModel::find($transaction->trucking->truck_id);
                if ($truck) {
                    $truck->truck_status = 'Available';
                    $truck->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Delivery status updated successfully',
                'transaction_id' => $transaction->receipt_id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error updating delivery status: ' . $e->getMessage()
            ], 500);
        }
    }
    public function syncTruckStatuses()
    {
        try {
            DB::beginTransaction();

            // Get all trucks
            $trucks = TruckModel::all();

            foreach ($trucks as $truck) {
                // Check if truck is assigned to any ongoing delivery
                $ongoingDelivery = TransactionModel::whereHas('trucking', function ($query) use ($truck) {
                    $query->where('truck_id', $truck->truck_id);
                })->where('status', 'On Going')->exists();

                // Update truck status accordingly
                $truck->truck_status = $ongoingDelivery ? 'In Use' : 'Available';
                $truck->save();
            }

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Truck statuses synchronized successfully']);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }

    }

}
