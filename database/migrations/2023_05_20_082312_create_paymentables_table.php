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
        Schema::create('paymentables', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('payment_id');
            $table->morphs('paymentable');
            $table->string('amount');
            $table->string('cost');
            $table->timestamps();
            $table->foreign('payment_id')->references('id')->on('payments');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paymentables');
    }
};
