<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CreportController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Route::post('save-transaction', [TransactionController::class, 'saveTransaction']);
Route::get('/api/cashier/report', [CreportController::class, 'index']);