<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('questions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('subject_id')->constrained()->onDelete('cascade');
        $table->foreignId('topic_id')->constrained()->onDelete('cascade');
        $table->enum('class_level', ['6', '9', '12']);
        $table->enum('difficulty', ['easy', 'medium', 'hard'])->default('medium');
        $table->enum('type', ['multiple_choice', 'true_false', 'short_answer'])
              ->default('multiple_choice');
        $table->longText('question_text');
        $table->string('question_image')->nullable();
        $table->text('option_a')->nullable();
        $table->text('option_b')->nullable();
        $table->text('option_c')->nullable();
        $table->text('option_d')->nullable();
        $table->text('option_e')->nullable();
        $table->string('correct_answer', 1); // a/b/c/d/e
        $table->longText('explanation_text')->nullable();
        $table->string('explanation_video_url')->nullable();
        $table->string('source')->nullable();
        $table->enum('status', ['draft', 'active', 'archived'])->default('draft');
        $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
        $table->timestamps();
    });
}
};
