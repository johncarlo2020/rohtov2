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
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_date_id')->constrained('booking_dates')->onDelete('cascade');
            $table->foreignId('booking_slot_id')->constrained('booking_slots')->onDelete('cascade');
            $table->string('reference_no')->unique();
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('venue')->default('LONGCHAMP POP UP STORE THE GARDENS MALL');
            $table->enum('status', ['pending', 'confirmed', 'cancelled', 'completed', 'attended', 'no_show'])->default('confirmed');
            $table->timestamp('attended_at')->nullable();
            $table->integer('reschedule_count')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
