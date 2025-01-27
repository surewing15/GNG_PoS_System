<?php

namespace App\Http\Controllers;

use App\Models\ProductModel;
use Illuminate\Http\Request;

class AproductController extends Controller
{
    public function index()
    {
        // Fetch all products from the database
        $products = ProductModel::all();

        // Fetch category options for the dropdown (using ENUM values)
        $categoryOptions = ProductModel::getCategoryOptions();

        // Pass both products and category options to the view
        return view('admin.pages.product.index', compact('products', 'categoryOptions'));
    }



    public function store(Request $request)
    {
        // Validate the form data
        $validatedData = $request->validate([
            'p_sku' => 'required|string|max:100',
            'p_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'category' => 'required|string',
            'p_description' => 'required|string|max:100',
            'p_price' => 'required|numeric|min:0', // Ensure the price is a valid numeric value
        ]);


        // Handle image upload if provided
        $imagePath = null; // Default null in case no image is uploaded
        if ($request->hasFile('p_image')) {
            $imagePath = $request->file('p_image')->store('products', 'public'); // Stores in 'storage/app/public/products'
        }

        // Create a new product instance
        $product = new ProductModel();
        $product->product_sku = $validatedData['p_sku'];
        $product->category = $validatedData['category'];
        $product->p_description = $validatedData['p_description'];
        $product->p_price = $validatedData['p_price']; // Save the price
        $product->img = $imagePath; // Save the image path
        $product->save();


        // Redirect back with a success message
        return redirect()->back()->with('success', 'Product added successfully.');
    }

}