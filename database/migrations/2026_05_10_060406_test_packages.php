<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    Schema::create('test_packages', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->text('description')->nullable();
        $table->enum('class_level', ['6', '9', '12']);
        $table->integer('total_questions')->default(0);
        $table->integer('duration_minutes')->default(60);
        $table->enum('type', ['free', 'premium'])->default('free');
        $table->boolean('is_randomized')->default(true);
        $table->timestamp('available_from')->nullable();
        $table->timestamp('available_until')->nullable();
        $table->enum('status', ['draft', 'published'])->default('draft');
        $table->foreignId('created_by')->constrained('users')->onDelete('cascade');
        $table->timestamps();
    });
}
};
