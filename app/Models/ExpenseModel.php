<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_expenses';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'e_description',
        'e_amount',
        'e_withdraw_by',
        'e_recieve_by',
        'e_return_amount',
        'e_return_by',
        'e_return_date',
        'e_return_description',
        'e_return_status',
        'e_return_reason',
    ];
    protected $casts = [
        'e_return_date' => 'datetime'
    ];
}