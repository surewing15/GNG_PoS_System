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
            ->whereIn('tbl_transactions.payment_type', ['cash', 'online'])
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
            'debits'
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
                    'debit_sales' => $this->getSalesByType($date, 'debit'),
                    'online_sales' => $this->getSalesByType($date, 'online')
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
        return TransactionModel::whereIn('payment_type', ['cash', 'online'])
            ->sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END'));
    }

    private function getTotalSalesData()
    {
        return TransactionModel::sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END'));
    }

    private function getSalesDataArray()
    {
        // Total sales (all payment types)
        $totalSales = TransactionModel::sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END'));

        // Cash and online sales total
        $totalCashSales = $this->getTotalCashSales();

        // Get last month's sales
        $lastMonth = Carbon::now()->subMonth();
        $lastMonthSales = TransactionModel::whereMonth('created_at', $lastMonth->month)
            ->whereYear('created_at', $lastMonth->year)
            ->sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END'));

        // This week's sales by payment type
        $thisWeek = Carbon::now()->startOfWeek();
        $thisWeekSales = [
            'cash' => TransactionModel::where('created_at', '>=', $thisWeek)
                ->where('payment_type', 'cash')
                ->sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END')),
            'online' => TransactionModel::where('created_at', '>=', $thisWeek)
                ->where('payment_type', 'online')
                ->sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END')),
            'debit' => TransactionModel::where('created_at', '>=', $thisWeek)
                ->where('payment_type', 'debit')
                ->sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END'))
        ];

        // Last week's sales by payment type
        $lastWeekStart = Carbon::now()->subWeek()->startOfWeek();
        $lastWeekEnd = Carbon::now()->subWeek()->endOfWeek();
        $lastWeekSales = [
            'cash' => TransactionModel::whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
                ->where('payment_type', 'cash')
                ->sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END')),
            'online' => TransactionModel::whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
                ->where('payment_type', 'online')
                ->sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END')),
            'debit' => TransactionModel::whereBetween('created_at', [$lastWeekStart, $lastWeekEnd])
                ->where('payment_type', 'debit')
                ->sum(DB::raw('CASE WHEN total_amount = 0 THEN subtotal ELSE total_amount END'))
        ];

        // Calculate total sales for this week and last week
        $thisWeekTotal = array_sum($thisWeekSales);
        $lastWeekTotal = array_sum($lastWeekSales);

        // Calculate percentage change
        $percentageChange = $lastWeekTotal ? (($thisWeekTotal - $lastWeekTotal) / $lastWeekTotal) * 100 : 0;

        return [
            'total_sales' => $totalSales,
            'total_cash_sales' => $totalCashSales,
            'last_month_sales' => $lastMonthSales,
            'this_week_sales' => $thisWeekTotal,
            'this_week_breakdown' => $thisWeekSales,
            'last_week_breakdown' => $lastWeekSales,
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
