<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ApurchaseHistoryController extends Controller
{
    public function index(){
        return view('admin.pages.history.purchase-history');
    }
}
