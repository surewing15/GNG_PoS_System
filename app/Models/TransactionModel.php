<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_transactions';
    protected $primaryKey = 'transaction_id';

    // Add explicit timestamp configuration
    public $timestamps = true;
    protected $dateFormat = 'Y-m-d H:i:s.u';

    protected $fillable = [
        'payment_type',
        'CustomerID',
        'service_type',
        'total_amount',
        'receipt_id',
        'date',
        'user_id',
        'subtotal',
        'amount_paid',
        'change_amount',
        'discount_percentage',
        'discount_amount',
        'status',
        'used_advance_payment',
        'reference_number',
        'created_at',
        'updated_at'
    ];

    protected $attributes = [
        'payment_type' => 'cash' // Default value
    ];

    protected $casts = [
        'amount_paid' => 'decimal:2',
        'change_amount' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'created_at' => 'datetime:Y-m-d H:i:s.u',
        'updated_at' => 'datetime:Y-m-d H:i:s.u',
        'date' => 'datetime:Y-m-d H:i:s.u'
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

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    protected static function booted()
    {
        static::creating(function ($model) {
            \Log::info('Server Timezone:', [
                'timezone' => config('app.timezone'),
                'server_time' => now(),
                'utc_time' => now()->utc()
            ]);
        });

        static::updating(function ($model) {
            // Update the updated_at timestamp with microseconds
            $model->updated_at = now()->format('Y-m-d H:i:s.u');
        });
    }

    /**
     * Get formatted created_at timestamp
     */
    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at ? $this->created_at->format('Y-m-d H:i:s.u') : null;
    }

    /**
     * Get formatted updated_at timestamp
     */
    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s.u') : null;
    }

    /**
     * Get formatted date timestamp
     */
    public function getFormattedDateAttribute()
    {
        return $this->date ? $this->date->format('Y-m-d H:i:s.u') : null;
    }
}