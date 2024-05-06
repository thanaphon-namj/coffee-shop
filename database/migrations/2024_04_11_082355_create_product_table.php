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
        Schema::create('Product', function (Blueprint $table) {
            $table->id('ProductID');
            $table->string('ProductName', length: 50);
            $table->text('Description')->nullable();
            $table->string('Category', length: 20);
            $table->double('Price');
            $table->integer('Stock')->default(0);
            $table->string('ImageUrl');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('Product');
    }
};
