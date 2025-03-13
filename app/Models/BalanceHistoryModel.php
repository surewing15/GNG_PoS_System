<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BalanceHistoryModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_balance_history';

    protected $fillable = [
        'customer_id',
        'receipt_id',
        'user_id',
        'previous_balance',
        'new_balance',
        'amount',
        'type',
        'description',
        'reference_id',
    ];

    public function customer()
    {
        return $this->belongsTo(CustomerModel::class, 'customer_id', 'CustomerID');
    }

    public function transaction()
    {
        return $this->belongsTo(TransactionModel::class, 'transaction_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}