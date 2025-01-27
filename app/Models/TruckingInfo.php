<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TruckingInfo extends Model
{
    use HasFactory;

    protected $table = 'trucking_info';
    protected $primaryKey = 'id';
    protected $fillable = [
        'receipt_no',
        'driver_id',
        'helper_id',
        'truck_id',
        'allowance',
        'destination',
        'CustomerID',
        'total_price',
        'total_kilo'
    ];

    // Update relationship definitions to match your database structure
    public function driver()
    {
        return $this->belongsTo(DriverModel::class, 'driver_id', 'driver_id');
    }

    public function truck()
    {
        return $this->belongsTo(TruckModel::class, 'truck_id', 'truck_id');
    }
    public function helper()
    {
        return $this->belongsTo(HelperModel::class, 'helper_id', 'helper_id');
    }

    // Timestamps are present in the table
    public $timestamps = true;
    const CREATED_AT = 'created_at';
    const UPDATED_AT = 'updated_at';
}