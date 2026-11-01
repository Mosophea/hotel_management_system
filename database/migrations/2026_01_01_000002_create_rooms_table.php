<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $t) {
            $t->id();
            $t->string('room_number')->unique();
            $t->foreignId('room_type_id')->constrained()->cascadeOnUpdate()->restrictOnDelete();
            $t->unsignedInteger('floor')->default(1);
            $t->enum('status', ['available', 'occupied', 'maintenance'])->default('available');
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
