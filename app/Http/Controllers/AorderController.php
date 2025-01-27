<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AorderController extends Controller
{
   public function index(){

    return view('admin.pages.order.index');

   }
}
