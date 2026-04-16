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
        Schema::create('parking_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('card_id')->constrained('cards');
            $table->foreignId('ticket_type_id')->constrained('ticket_types');
            $table->string('license_plate');
            $table->dateTime('check_in_time');
            $table->dateTime('check_out_time')->nullable()->default(null);
            $table->foreignId('staff_id_in')->constrained('users');
            $table->foreignId('staff_id_out')->nullable()->constrained('users');
            $table->enum('status', ['parking', 'completed'])->default('parking');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parking_sessions');
    }
};
