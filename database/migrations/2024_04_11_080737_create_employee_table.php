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
        Schema::create('Employee', function (Blueprint $table) {
            $table->id('EmployeeID');
            $table->string('FirstName', length: 20);
            $table->string('LastName', length: 20);
            $table->date('BirthDate');
            $table->string('PhoneNo', length: 15);
            $table->string('Email', length: 50);
            $table->string('Position', length: 20);
            $table->integer('Salary');
            $table->date('StartDate');
            $table->date('EndDate')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Employee');
    }
};
