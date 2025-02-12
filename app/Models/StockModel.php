<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_stock';
    protected $primaryKey = 'stock_id';

    protected $fillable = [
        'product_id',
        'stock_kilos',
        'price',
        'head',
        'dr'

    ];

    public $timestamps = true;

    // Relationship with Product
    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_id', 'product_id');
    }

    // Relationship with Stock History
    public function stockHistory()
    {
        return $this->hasMany(StockHistoryModel::class, 'stock_id');
    }


    // Automatically create stock history upon update
    protected static function booted()
    {
        static::updated(function ($stock) {
            StockHistoryModel::create([
                'stock_id' => $stock->stock_id,
                'stock_quantity' => $stock->stock_quantity,
                'updated_at' => now(),
            ]);
        });
    }
}