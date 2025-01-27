<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashierRecieptController extends Controller
{
    public function index()
    {
        return view('cashier.modal.reciept');
    }
}