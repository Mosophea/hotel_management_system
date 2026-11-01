<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $t) {
            $t->id();
            $t->foreignId('booking_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $t->decimal('amount', 10, 2);
            $t->enum('payment_method', ['cash', 'card', 'aba', 'other']);
            $t->date('paid_at')->nullable();
            $t->enum('status', ['paid', 'pending', 'refunded'])->default('pending');
            $t->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
