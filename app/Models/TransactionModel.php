<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_transactions';
    protected $primaryKey = 'transaction_id';

    protected $fillable = [
        'payment_type',
        'CustomerID',  // Add this to fillable
        'service_type',
        'total_amount',
        'receipt_id',
        'date',
        'subtotal',
        'amount_paid',
        'change_amount',
        'discount_percentage',
        'discount_amount',
        'status',
        'created_at',
        'updated_at'
    ];
    protected $attributes = [
        'payment_type' => 'cash' // Set default value
    ];
    protected $casts = [
        'amount_paid' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2'
    ];
    const STATUS_NOT_ASSIGNED = 'Not Assigned';
    const STATUS_ON_GOING = 'On Going';
    const STATUS_SUCCESSFUL = 'Successful';
    /**
     * Define relationship with TransactionItemModel
     */
    public function items()
    {
        return $this->hasMany(TransactionItemModel::class, 'transaction_id', 'transaction_id');
    }
    public function customer()
    {
        return $this->belongsTo(CustomerModel::class, 'CustomerID', 'CustomerID');
    }
    public function trucking()
    {
        return $this->hasOne(TruckingInfo::class, 'receipt_no', 'receipt_id');
    }
    protected static function booted()
    {
        static::creating(function ($model) {
            if (!isset($model->payment_type)) {
                $model->payment_type = 'cash';
            }
            \Log::info('Transaction creating:', $model->toArray());
        });
    }


}