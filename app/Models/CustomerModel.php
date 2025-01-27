<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerModel extends Model
{
    use HasFactory;

    // Define the table name if it differs from the default
    protected $table = 'tbl_customers';
    protected $primaryKey = 'CustomerID';
    // Specify which columns are mass assignable
    protected $fillable = [
        'FirstName',
        'LastName',
        'Address',
        'PhoneNumber',
        'Balance',
         'status',
         'Collection_ID',
        'payment_type'


    ];
    protected $attributes = [
        'status' => 'Pending'
    ];
    public function transactions()
    {
        return $this->hasMany(TransactionModel::class, 'CustomerID', 'CustomerID');
    }
    public function payments()
    {
        return $this->hasMany(PaymentModel::class, 'customer_id', 'CustomerID');
    }

}