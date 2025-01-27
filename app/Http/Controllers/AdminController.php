<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\TransactionModel;
use App\Models\CustomerModel;
use App\Models\ExpenseModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index()
    {
        $totalSales = $this->getTotalSalesData();
        $totalCashSales = $this->getTotalCashSales();
        $salesData = $this->getSalesDataArray();
        $expenses = ExpenseModel::all();
        $totalExpenses = $expenses->sum('e_amount');
        $totalCash = max($totalCashSales - $totalExpenses, 0);
        $weeklySales = $this->getWeeklySalesData();

        $customers = TransactionModel::select(
            'tbl_transactions.CustomerID',
            'tbl_customers.FirstName',
            'tbl_customers.LastName',
            'tbl_transactions.receipt_id',
            DB::raw('CASE WHEN tbl_transactions.total_amount = 0 THEN tbl_transactions.subtotal ELSE tbl_transactions.total_amount END as final_amount')
        )
            ->join('tbl_customers', 'tbl_transactions.CustomerID', '=', 'tbl_customers.CustomerID')
            ->where('tbl_transactions.payment_type', 'cash')
            ->distinct()
            ->get();

    $debits = $this->getDebitTransactions();

        $totalCustomerCount = CustomerModel::count('CustomerID');
        $lastWeekCustomerCount = TransactionModel::whereBetween('created_at', [
            Carbon::now()->subWeek()->startOfWeek(),
            Carbon::now()->subWeek()->endOfWeek()
        ])->count('CustomerID');

        $percentageChange = $lastWeekCustomerCount ?
            (($totalCustomerCount - $lastWeekCustomerCount) / $lastWeekCustomerCount) * 100 : 0;

            return view('dashboard', compact(
                'totalSales',
                'totalCashSales',
                'totalExpenses',
                'totalCash',
                'salesData',
                'expenses',
                'totalCustomerCount',
                'percentageChange',
                'customers',
                'weeklySales',
                'debits' // Added missing debits
            ));
    }

    public function getWeeklySalesData()
    {
        return collect(range(0, 6))
            ->map(function ($days) {
                $date = Carbon::now()->subDays($days);
                return [
                    'name' => $date->format('D'),
                    'cash_sales' => $this->getSalesByType($date, 'cash'),
                    'debit_sales' => $this->getSalesByType($date, 'debit')
                ];
            })
            ->reverse()
            ->values();
    }

    private function getSalesByType($date, $type)
    {
        return TransactionModel::whereDate('created_at', $date)
            ->where('payment_type', $type)
            ->sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END'));
    }

    private function getTotalCashSales()
    {
        return TransactionModel::where('payment_type', 'cash')
            ->sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END'));
    }

    private function getTotalSalesData()
    {
        return TransactionModel::sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END'));
    }

    private function getSalesDataArray()
    {
        $totalSales = TransactionModel::sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END'));
        $totalCashSales = $this->getTotalCashSales();

        $lastMonth = Carbon::now()->subMonth();
        $lastMonthSales = TransactionModel::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END'));

        $thisWeek = Carbon::now()->startOfWeek();
        $thisWeekSales = TransactionModel::where('created_at', '>=', $thisWeek)
            ->sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END'));

        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();
        $lastWeekSales = TransactionModel::whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
            ->sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END'));

        $percentageChange = $lastWeekSales ? (($thisWeekSales - $lastWeekSales) / $lastWeekSales) * 100 : 0;

        return [
            'total_sales' => $totalSales,
            'total_cash_sales' => $totalCashSales,
            'last_month_sales' => $lastMonthSales,
            'this_week_sales' => $thisWeekSales,
            'percentage_change' => $percentageChange,
        ];
    }

    private function getDebitTransactions()
    {
        return TransactionModel::where('payment_type', 'debit')
            ->select(
                'tbl_transactions.receipt_id',
                'tbl_transactions.subtotal',
                'tbl_transactions.created_at',
                'tbl_transactions.CustomerID',
                'tbl_customers.FirstName',
                'tbl_customers.LastName',
                DB::raw('CASE WHEN tbl_transactions.total_amount = 0 THEN tbl_transactions.subtotal ELSE tbl_transactions.total_amount END as final_amount')
            )
            ->join('tbl_customers', 'tbl_transactions.CustomerID', '=', 'tbl_customers.CustomerID')
            ->distinct()
            ->get();
    }

}