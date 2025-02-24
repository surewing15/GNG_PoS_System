<?php

// AdvancePaymentHistoryModel.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdvancePaymentHistoryModel extends Model
{
    protected $table = 'tbl_advance_payment_history';
    protected $primaryKey = 'id';

    protected $fillable = [
        'customer_id',
        'user_id',
        'amount',
        'type', // 'deposit' or 'used'
        'previous_balance',
        'new_balance',
        'reference_id', // For linking to transactions if payment is used
        'notes'
    ];

    public function customer()
    {
        return $this->belongsTo(CustomerModel::class, 'customer_id', 'CustomerID');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
