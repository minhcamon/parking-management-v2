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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('session_id')->nullable()->constrained('parking_sessions');
            $table->foreignId('monthly_pass_id')->nullable()->constrained('monthly_passes');
            $table->decimal('amount', 10, 2);
            $table->dateTime('payment_time')->nullable();
            $table->foreignId('staff_id')->nullable()->constrained('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
