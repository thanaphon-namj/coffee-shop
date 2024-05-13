<?php

use App\Models\Customer;
use App\Models\Employee;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('Order', function (Blueprint $table) {
            $table->id('OrderID');
            $table->double('Total');
            $table->string('Payment', length: 15);
            $table->date('PurchaseDate');
            $table
                ->foreignId('EmployeeID')
                ->references('EmployeeID')
                ->on('Employee');
            $table
                ->foreignId('CustomerID')
                ->nullable()
                ->references('CustomerID')
                ->on('Customer');
        });

        Schema::create('OrderDetail', function (Blueprint $table) {
            $table->id('OrderDetailID');
            $table->integer('Quantity');
            $table->double('Total');
            $table
                ->foreignId('OrderID')
                ->references('OrderID')
                ->on('Order')
                ->cascadeOnDelete();
            $table
                ->foreignId('ProductID')
                ->references('ProductID')
                ->on('Product');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('OrderDetail');
        Schema::dropIfExists('Order');
    }
};
