<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashDenominationModel extends Model
{
    use HasFactory;
    protected $table = 'tbl_denominations';
    protected $fillable = [
        'user_id',
        'count_date',
        'd1000',
        'd500',
        'd200',
        'd100',
        'd50',
        'd20',
        'd10',
        'd5',
        'd1',
        'd0.25',          // Added
        'online_amount', // Added
        'total_amount'
    ];
    protected $casts = [
        'count_date' => 'date',
        'online_amount' => 'decimal:2',
        'total_amount' => 'decimal:2'
    ];
}