<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ASalesrepController extends Controller
{
    public function wholesales(){

        return view('admin.pages.reports.index');
    }


    public function salesretail(){

        return view('admin.pages.reports.salesretail');
    }

    public function eggsales(){

        return view('admin.pages.reports.eggsales');
    }

    public function denomination(){

        return view('admin.pages.reports.denomination');
    }


}
