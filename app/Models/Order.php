<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $table = "Order";

    protected $fillable = [
        "Total",
        "Payment",
        "PurchaseDate",
        "EmployeeID",
        "CustomerID",
    ];

    public $timestamps = false;

    public function details(): HasMany
    {
        return $this->hasMany(OrderDetail::class, "OrderID");
    }
}
