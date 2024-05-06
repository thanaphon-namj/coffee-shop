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
        Schema::create('Customer', function (Blueprint $table) {
            $table->id('CustomerID');
            $table->string('FirstName', length: 20);
            $table->string('LastName', length: 20);
            $table->date('BirthDate')->nullable();
            $table->string('PhoneNo', length: 15);
            $table->integer('Points')->default(0);
            $table->date('RegisterDate');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Customer');
    }
};
