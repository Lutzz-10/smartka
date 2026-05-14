<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('ai_chat_messages', function (Blueprint $table) {
        $table->id();
        $table->foreignId('session_id')->constrained('ai_chat_sessions')->onDelete('cascade');
        $table->enum('role', ['user', 'model']);
        $table->longText('content');
        $table->string('image_path')->nullable();
        $table->boolean('is_starred')->default(false);
        $table->enum('feedback', ['helpful', 'not_helpful'])->nullable();
        $table->timestamps();
    });
}
};
