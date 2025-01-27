<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TruckModel extends Model
{
    protected $table = 'tbl_truck';
    protected $primaryKey = 'truck_id';

    const STATUS_AVAILABLE = 'Available';
    const STATUS_IN_USE = 'In Use';
    const STATUS_MAINTENANCE = 'Maintenance';
    const STATUS_OUT_OF_SERVICE = 'Out of Service';

    protected $fillable = [
        'truck_name',
        'truck_type',
        'truck_status'
    ];

    public static function getStatusList()
    {
        return [
            self::STATUS_AVAILABLE,
            self::STATUS_IN_USE,
            self::STATUS_MAINTENANCE,
            self::STATUS_OUT_OF_SERVICE
        ];
    }

    // Remove redundant relationships - they should be in TruckingInfo
    // public function truck()
    // {
    //     return $this->belongsTo(TruckModel::class, 'truck_id');
    // }

    // These relationships should be through TruckingInfo
    public function truckings()
    {
        return $this->hasMany(TruckingInfo::class, 'truck_id', 'truck_id');
    }
}