<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('inspection_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inspection_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('comment_type', ['qa_review', 'revision_response', 'general', 'system']);
            $table->text('comment');
            $table->enum('status', ['active', 'deleted'])->default('active');
            $table->json('metadata')->nullable(); // For storing additional data like QA status, etc.
            $table->timestamps();
            
            $table->index(['inspection_id', 'created_at']);
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inspection_comments');
    }
};