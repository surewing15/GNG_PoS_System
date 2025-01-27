<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AinventoryController extends Controller
{
    public function index()
    {
        return view('admin.pages.inventory.index');
    }

    
}
