<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // database/migrations/xxxx_create_users_table.php
public function up(): void
{
    Schema::create('users', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('phone')->nullable();
        $table->enum('role', ['student', 'admin', 'author'])->default('student');
        $table->enum('class_level', ['6', '9', '12'])->nullable();
        $table->string('avatar')->nullable();
        $table->timestamp('email_verified_at')->nullable();
        $table->string('otp_code', 6)->nullable();
        $table->timestamp('otp_expires_at')->nullable();
        $table->enum('subscription_status', ['free', 'premium', 'premium_plus'])->default('free');
        $table->timestamp('subscription_ends_at')->nullable();
        $table->rememberToken();
        $table->timestamps();
    });
}
};
