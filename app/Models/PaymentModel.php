<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentModel extends Model
{
    use HasFactory;


    protected $table = 'tbl_payment';


    protected $fillable = [
        'customer_id',
        'user_id',
        'amount',
        'payment_method',
        'payment_date',
        'notes',
        'status',
        'check_number',
        'bank_name',
        'Collection_ID',
        'bank_number',
    ];


    protected $casts = [
        'payment_date' => 'datetime',
        'amount' => 'decimal:2',
    ];
    public function customer()
    {
        return $this->belongsTo(CustomerModel::class, 'customer_id', 'CustomerID');
    }
}