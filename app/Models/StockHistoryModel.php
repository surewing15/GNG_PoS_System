<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockHistoryModel extends Model
{
    protected $table = 'tbl_stock_history';
    protected $primaryKey = 'history_id';
    protected $fillable = ['stock_id','created_at', 'updated_at'];


    public function stock()
    {
        return $this->belongsTo(StockModel::class,  'stock_id');
    }
}