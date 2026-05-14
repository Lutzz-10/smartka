<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('user_answers', function (Blueprint $table) {
        $table->id();
        $table->foreignId('session_id')->constrained('user_sessions')->onDelete('cascade');
        $table->foreignId('question_id')->constrained()->onDelete('cascade');
        $table->string('selected_answer', 1)->nullable();
        $table->boolean('is_correct')->default(false);
        $table->boolean('is_marked')->default(false);
        $table->boolean('hint_used')->default(false);
        $table->integer('time_spent_seconds')->default(0);
        $table->timestamps();
    });
}
};
