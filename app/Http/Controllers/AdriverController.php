<?php

namespace App\Http\Controllers;
use App\Models\DriverModel;
use Illuminate\Http\Request;

class AdriverController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $drivers = DriverModel::all();  // Get all drivers from the database
        return view('admin.pages.driver.index', compact('drivers'));  // Pass data to the view
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validat  e the incoming request
        $request->validate([
            'inp_fn' => 'required|string|max:255',
            'inp_ln' => 'required|string|max:255',
            'inp_phone' => 'required|string|max:15',
        ]);

        // Create a new driver record
        DriverModel::create([
            'fname' => $request->inp_fn,
            'lname' => $request->inp_ln,
            'mobile_no' => $request->inp_phone,
        ]);

        // Redirect back with success message
        return redirect()->route('driver.index')->with('success', 'Driver added successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}