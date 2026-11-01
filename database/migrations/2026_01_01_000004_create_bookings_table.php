<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $t) {
            $t->id();
            $t->foreignId('guest_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('room_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->foreignId('user_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->date('check_in');
            $t->date('check_out');
            $t->decimal('total_amount', 10, 2);
            $t->enum('status', ['confirmed', 'checked_in', 'checked_out', 'cancelled'])->default('confirmed');
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
