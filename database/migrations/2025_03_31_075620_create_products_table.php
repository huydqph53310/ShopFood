<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained();
            $table->string(column: 'code');
            $table->string(column: 'name');
            $table->string(column: 'image')->nullable();
            $table->string(column: 'description')->nullable();
            $table->string(column: 'material')->nullable();
            $table->string(column: 'instruct')->nullable();
            $table->string(column: 'onpage')->nullable();
            $table->string(column: 'status')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
