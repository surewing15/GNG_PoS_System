<?php

namespace App\Http\Controllers;
use App\Models\TruckModel;
use Illuminate\Http\Request;

class AtruckController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trucks = TruckModel::all(); // Fetch all trucks from the database
        return view('admin.pages.truck.index', compact('trucks'));
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
        // Validate input
        $request->validate([
            'truck_name' => 'required|string|max:255',
            'truck_type' => 'required|string|max:255',
            'truck_status' => 'required|string|max:255',
        ]);

        // Save the truck data
        TruckModel::create([
            'truck_name' => $request->input('truck_name'),
            'truck_type' => $request->input('truck_type'),
            'truck_status' => $request->input('truck_status'),
        ]);

        // Redirect with success message
        return redirect()->route('truck.index')->with('success', 'Truck record saved successfully!');
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $truck = TruckModel::findOrFail($id); // Fetch truck by ID
        return view('admin.pages.truck.show', compact('truck')); // Pass truck data to the view
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $truck = TruckModel::findOrFail($id); // Fetch truck by ID
        return view('admin.pages.truck.edit', compact('truck')); // Pass truck data to the edit form
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'truck_name' => 'required|string|max:255',
            'truck_type' => 'required|string|max:255',
            'truck_status' => 'required|string|max:255',
        ]);

        $truck = TruckModel::findOrFail($id);
        $truck->update($request->all()); // Update truck data
        return redirect()->route('truck.index')->with('success', 'Truck updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $truck = TruckModel::findOrFail($id);
        $truck->delete(); // Delete the truck
        return redirect()->route('truck.index')->with('success', 'Truck deleted successfully!');
    }
}
