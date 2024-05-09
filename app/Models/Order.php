<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Order extends Model
{
    use HasFactory;

    protected $table = 'Order';

    protected $primaryKey = 'OrderID';

    protected $fillable = [
        'Total',
        'Payment',
        'PurchaseDate',
        'EmployeeID',
        'CustomerID',
    ];

    public $timestamps = false;

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'EmployeeID');
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class, 'CustomerID');
    }
}
