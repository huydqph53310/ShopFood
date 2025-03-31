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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('address');
            $table->string('phone');
            $table->string('email')->nullable();
            $table->string('status');
            $table->string('payment');
            $table->decimal('total',10,2)->default(0);
            $table->string('voucher_code');
            $table->decimal('sale_price',8,0)->default(0);
            $table->decimal('pay_amount',8,0)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
