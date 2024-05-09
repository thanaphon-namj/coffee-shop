<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderDetail extends Model
{
    use HasFactory;

    protected $table = 'OrderDetail';

    protected $primaryKey = 'OrderDetailID';

    protected $fillable = ['Quantity', 'Total', 'OrderID', 'ProductID'];

    public $timestamps = false;

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'OrderID');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'ProductID');
    }
}
