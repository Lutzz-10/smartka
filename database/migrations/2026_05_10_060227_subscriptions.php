<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
    Schema::create('subscriptions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->enum('plan', ['premium', 'premium_plus']);
        $table->date('start_date');
        $table->date('end_date');
        $table->enum('payment_status', ['pending', 'success', 'failed', 'expired']);
        $table->decimal('amount', 12, 2);
        $table->string('payment_method')->nullable();
        $table->string('transaction_id')->nullable();
        $table->timestamps();
    });
}
};
