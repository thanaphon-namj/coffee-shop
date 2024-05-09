<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'Product';

    protected $primaryKey = 'ProductID';

    protected $fillable = [
        'ProductName',
        'Description',
        'Category',
        'Price',
        'Stock',
        'ImageUrl',
    ];

    public $timestamps = false;
}
