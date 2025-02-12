<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterStockModel extends Model
{
    use HasFactory;


    protected $table = 'tbl_master_stock';

    protected $primaryKey = 'master_stock_id';

    protected $fillable = [
        'product_id',
        'total_all_kilos',
        'price',
        'total_head',
        'dr'
    ];

    public $timestamps = false;

    public function stock()
    {
        return $this->belongsTo(StockModel::class, 'product_id', 'product_id');
    }
    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_id', 'product_id');
    }
}