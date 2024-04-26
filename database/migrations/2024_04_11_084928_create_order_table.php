<?php

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
            $table->string('Payment', length: 10);
            $table->date('PurchaseDate');
            $table
                ->foreignId('EmployeeID')
                ->constrained(table: 'Employee', column: 'EmployeeID');
            $table
                ->foreignId('CustomerID')
                ->constrained(table: 'Customer', column: 'CustomerID');
        });

        Schema::create('OrderDetail', function (Blueprint $table) {
            $table->id('OrderDetailID');
            $table->integer('Quantity');
            $table->double('Total');
            $table
                ->foreignId('OrderID')
                ->constrained(table: 'Order', column: 'OrderID');
            $table
                ->foreignId('ProductID')
                ->constrained(table: 'Product', column: 'ProductID');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Order');
        Schema::dropIfExists('OrderDetail');
    }
};
