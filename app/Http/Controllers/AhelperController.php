<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HelperModel;
class AhelperController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $helpers = HelperModel::all();
        return view('admin.pages.helper.index', compact('helpers'));
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
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:15',
        ]);

        // Create a new helper record
        HelperModel::create([
            'fname' => $request->input('fname'),
            'lname' => $request->input('lname'),
            'mobile_no' => $request->input('mobile_no'),
        ]);

        // Redirect back with success message
        return redirect()->route('helper.index')->with('success', 'Helper added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $helper = HelperModel::findOrFail($id);
        return view('admin.pages.helper.show', compact('helper'));
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $helper = HelperModel::findOrFail($id);
        return view('admin.pages.helper.edit', compact('helper'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'mobile_no' => 'required|string|max:15',
        ]);

        $helper = HelperModel::findOrFail($id);
        $helper->update([
            'fname' => $request->input('fname'),
            'lname' => $request->input('lname'),
            'mobile_no' => $request->input('mobile_no'),
        ]);

        return redirect()->route('helper.index')->with('success', 'Helper updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $helper = HelperModel::findOrFail($id);
        $helper->delete();

        return redirect()->route('helper.index')->with('success', 'Helper deleted successfully.');
    }

}