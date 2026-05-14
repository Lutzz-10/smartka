<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('user_sessions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('test_package_id')->constrained()->onDelete('cascade');
        $table->timestamp('started_at')->nullable();
        $table->timestamp('finished_at')->nullable();
        $table->enum('status', ['ongoing', 'completed', 'abandoned'])->default('ongoing');
        $table->integer('time_spent_seconds')->default(0);
        $table->timestamps();
    });
}
};
