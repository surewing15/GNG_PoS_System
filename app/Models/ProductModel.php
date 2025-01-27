<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\DB;

class ProductModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_product';
    protected $primaryKey = 'product_id';

    protected $fillable = [
        'product_sku',
        'category',
        'img',
    ];

    public static function getCategoryOptions()
    {
        $type = DB::select("SHOW COLUMNS FROM tbl_product WHERE Field = 'category'");

        if (isset($type[0]->Type)) {
            preg_match('/^enum\((.*)\)$/', $type[0]->Type, $matches);

            $enum = [];
            foreach (explode(',', $matches[1]) as $value) {
                $enum[] = trim($value, "'");
            }

            return $enum;
        }

        return [];
    }

    public function stocks()
    {
        return $this->hasMany(StockModel::class, 'product_id', 'product_id');
    }

    // Add relationship to MasterStockModel
    public function masterStocks()
    {
        return $this->hasMany(MasterStockModel::class, 'product_id', 'product_id');
    }
}