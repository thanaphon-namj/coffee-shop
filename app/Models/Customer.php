<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $table = 'Customer';

    protected $primaryKey = 'CustomerID';

    protected $fillable = [
        'FirstName',
        'LastName',
        'BirthDate',
        'PhoneNo',
        'Points',
        'RegisterDate',
    ];

    public $timestamps = false;
}
