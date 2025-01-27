<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerModel;
use App\Models\PaymentModel;
use Illuminate\Support\Facades\DB;

class CcustomerController extends Controller
{
    public function index()
    {
        $customers = CustomerModel::with([
            'payments' => function ($query) {
                $query->orderBy('payment_date', 'desc');
            }
        ])->get();

        return view('cashier.pages.customers.index', compact('customers'));
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'inp_fn' => 'required|string|max:255',
            'inp_ls' => 'required|string|max:255',
            'inp_address' => 'required|string|max:255',
            'inp_phone' => 'required|numeric',
            'inp_balance' => 'required|numeric|min:0',
        ]);

        try {
            $prefix = '#CL' . date('Ymd');
            $lastId = CustomerModel::where('Collection_ID', 'LIKE', $prefix . '%')
                ->max('Collection_ID');

            if ($lastId) {
                $number = intval(substr($lastId, -3)) + 1;
            } else {
                $number = 1;
            }

            $nextCollectionId = $prefix . str_pad($number, 3, '0', STR_PAD_LEFT);

            CustomerModel::create([
                'Collection_ID' => $nextCollectionId,
                'FirstName' => $validated['inp_fn'],
                'LastName' => $validated['inp_ls'],
                'Address' => $validated['inp_address'],
                'PhoneNumber' => $validated['inp_phone'],
                'Balance' => $validated['inp_balance'],
            ]);

            return redirect()->back()->with('success', 'Customer saved successfully!');
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return redirect()->back()->with('error', 'An error occurred while saving the customer.');
        }
    }
    public function show($id)
    {
        return CustomerModel::with([
            'payments' => function ($query) {
                $query->orderBy('payment_date', 'desc');
            }
        ])->findOrFail($id);
    }
    public function storePayment(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:tbl_customers,CustomerID',
            'amount' => 'required|numeric|min:0',
            'payment_method' => 'required|in:cash,check,bank',
            'payment_date' => 'required|date',
            'notes' => 'nullable|string',
            'check_number' => 'required_if:payment_method,check',
            'check_bank_name' => 'required_if:payment_method,check',
            'bank_number' => 'required_if:payment_method,bank',
            'bank_name' => 'required_if:payment_method,bank'
        ]);

        DB::transaction(function () use ($validated) {
            $payment = PaymentModel::create($validated);

            $customer = CustomerModel::find($validated['customer_id']);
            $customer->decrement('balance', $validated['amount']);
        });

        return redirect()->back()->with('success', 'Payment recorded successfully');
    }
    // In your Controller:
    public function getCollectionDetails($id)
    {
        $customer = CustomerModel::find($id);
        $status = ($customer->Balance == 0) ? 'Paid' : 'Pending';

        return response()->json([
            'balance' => $customer->Balance,
            'status' => $status,
            'collection_id' => $id
        ]);
    }
}