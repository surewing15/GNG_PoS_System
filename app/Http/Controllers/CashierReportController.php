<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CashierReportController extends Controller
{
    public function wholesale(){

        return view('cashier.pages.reports.wholesale');
    }

    public function eggsales(){

        return view('cashier.pages.reports.eggsales');
    }

    public function salesretail(){

        return view('cashier.pages.reports.salesretail');
    }

    public function denomination(){

        return view('cashier.pages.reports.denomination');
    }
}
