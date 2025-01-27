<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UbyproductController extends Controller
{
  public function index(){
    return view('user.pages.by-product.index');

  }
}
