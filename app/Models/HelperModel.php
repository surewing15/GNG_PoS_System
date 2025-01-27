<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class HelperModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_helper';
    protected $primaryKey = 'helper_id';

    protected $fillable = [
        'fname',
        'lname',
        'helper_name',
        'mobile_no',
    ];

    public function truckings()
    {
        return $this->hasMany(TruckingInfo::class, 'helper_id', 'helper_id');
    }
}