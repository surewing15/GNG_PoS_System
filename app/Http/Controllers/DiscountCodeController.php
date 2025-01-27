<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DiscountCodeController extends Controller
{
    public function index(){

        return view('admin.pages.permissions.discount');
    }
}
