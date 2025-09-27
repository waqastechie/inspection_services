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
        // This migration is no longer needed as the QA workflow migration already provides proper status values
        // The QA workflow enum already includes: 'draft', 'submitted_for_qa', 'under_qa_review', 'qa_approved', 'qa_rejected', 'revision_required', 'completed', 'cancelled'
        // No changes needed - the enum is already correct
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to previous ENUM values
        DB::statement("ALTER TABLE inspections MODIFY COLUMN status ENUM('draft', 'in_progress', 'completed', 'approved') DEFAULT 'draft'");
    }
};
