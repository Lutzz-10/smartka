<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('payments', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->enum('plan', ['premium', 'premium_plus']);
        $table->decimal('amount', 12, 2);
        $table->string('payment_method')->nullable();
        $table->string('gateway_transaction_id')->nullable();
        $table->enum('status', ['pending', 'success', 'failed'])->default('pending');
        $table->json('callback_payload')->nullable();
        $table->timestamp('paid_at')->nullable();
        $table->timestamps();
    });
}
};
