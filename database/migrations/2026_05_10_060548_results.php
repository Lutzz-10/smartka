<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('results', function (Blueprint $table) {
        $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');
        $table->foreignId('session_id')->constrained('user_sessions')->onDelete('cascade');
        $table->decimal('total_score', 5, 2)->default(0);
        $table->integer('correct_count')->default(0);
        $table->integer('wrong_count')->default(0);
        $table->integer('empty_count')->default(0);
        $table->json('score_per_subject')->nullable();   // {"math": 80, "ipa": 60}
        $table->json('weakness_topics')->nullable();     // ["Limit Fungsi", "SPLDV"]
        $table->timestamps();
    });
}
};
