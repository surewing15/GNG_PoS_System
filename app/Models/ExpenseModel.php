<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class ExpenseModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_expenses';

    protected $fillable = [
        'e_description',
        'e_amount',
        'e_withdraw_by',
          'e_recieve_by',



    ];
}