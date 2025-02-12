<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionItemModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_transaction_items';
    protected $primaryKey = 'item_id';

    protected $fillable = [
        'transaction_id',
        "user_id",
        'dr',
        'product_id',
        'kilos',
        'price_per_kilo',
        'total',
        'head',
    ];

    /**
     * Define relationship with TransactionModel
     */
    public function transaction()
    {
        return $this->belongsTo(TransactionModel::class, 'transaction_id', 'transaction_id');
    }

    public function product()
    {
        return $this->belongsTo(ProductModel::class, 'product_id', 'product_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}