<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CTruckingInvoiceController extends Controller
{
    public function index(){
        return view('cashier.pages.invoice.index');
    }
}
