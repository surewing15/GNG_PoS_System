<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CustomerModel;

class AcustomerController extends Controller
{
    public function index()
    {
        $customers = CustomerModel::all();
        return view('admin.pages.customer.index', compact('customers'));
    }


    public function edit()
    {
        return view('admin.pages.customer.edit');
    }
    public function store(Request $request)
    {
        // Validate the form input
        $request->validate([
            'cus_name' => 'required|string|max:255',
            'cus_address' => 'required|string|max:255',
            'cus_phonenumber' => 'required|string|max:15',
        ]);

        // Insert the customer data into the database
        CustomerModel::create([
            'Cust_name' => $request->cus_name,
            'Cust_address' => $request->cus_address,
            'phone_number' => $request->cus_phonenumber,
        ]);

        // Redirect back with success message
        return redirect()->back()->with('success', 'Customer added successfully!');
    }

}
