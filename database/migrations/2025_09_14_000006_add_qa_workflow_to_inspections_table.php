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
        Schema::table('inspections', function (Blueprint $table) {
            // Add QA workflow fields
            $table->enum('qa_status', ['pending_qa', 'under_qa_review', 'qa_approved', 'qa_rejected', 'revision_required'])->default('pending_qa')->after('status');
            $table->foreignId('qa_reviewer_id')->nullable()->constrained('users')->after('qa_status');
            $table->timestamp('qa_reviewed_at')->nullable()->after('qa_reviewer_id');
            $table->text('qa_comments')->nullable()->after('qa_reviewed_at');
            $table->text('qa_rejection_reason')->nullable()->after('qa_comments');
            
            // Update the existing status enum to include qa-related statuses
            $table->enum('status', ['draft', 'submitted_for_qa', 'under_qa_review', 'qa_approved', 'qa_rejected', 'revision_required', 'completed', 'cancelled'])->default('draft')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('inspections', function (Blueprint $table) {
            $table->dropForeign(['qa_reviewer_id']);
            $table->dropColumn(['qa_status', 'qa_reviewer_id', 'qa_reviewed_at', 'qa_comments', 'qa_rejection_reason']);
            
            // Revert status enum
            $table->enum('status', ['draft', 'in_progress', 'completed', 'cancelled'])->default('draft')->change();
        });
    }
};
