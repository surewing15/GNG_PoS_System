<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CstocksController extends Controller
{
    public function index(){

        return view('clerk.pages.stocks.index');
    }
}
