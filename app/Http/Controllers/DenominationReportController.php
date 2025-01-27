<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DenominationReportController extends Controller
{
    public function index(){
        return view('admin.pages.reports.denomination');
    }
}
