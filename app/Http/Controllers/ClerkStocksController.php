<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ClerkStocksController extends Controller
{
    public function index(){
        return view('clerk.pages.stocklist.index');
    }
}
