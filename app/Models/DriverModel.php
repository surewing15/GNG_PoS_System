<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriverModel extends Model
{
    use HasFactory;

    protected $table = 'tbl_driver';
    protected $primaryKey = 'driver_id';

    protected $fillable = [
        'fname',
        'lname',
        'mobile_no'
    ];

    // Add accessor for full name
    public function getFullNameAttribute()
    {
        return "{$this->fname} {$this->lname}";
    }
}