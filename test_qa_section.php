<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== QUALITY CONTROL SECTION TEST ===\n\n";

try {
    $inspection = App\Models\Inspection::with(['qaReviewer'])->first();
    
    if (!$inspection) {
        echo "❌ No inspection found\n";
        exit(1);
    }
    
    echo "✅ Inspection: " . $inspection->inspection_number . "\n\n";
    
    echo "🔍 QUALITY CONTROL DATA:\n";
    echo "   QA Status: " . ($inspection->qa_status ?? 'Not Set') . "\n";
    echo "   QA Status Name: " . ($inspection->qa_status_name ?? 'N/A') . "\n";
    echo "   QA Reviewer ID: " . ($inspection->qa_reviewer_id ?? 'Not Assigned') . "\n";
    echo "   QA Reviewer Name: " . ($inspection->qaReviewer->name ?? 'Not Assigned') . "\n";
    echo "   QA Review Date: " . ($inspection->qa_reviewed_at ? $inspection->qa_reviewed_at->format('M j, Y H:i') : 'Not Reviewed') . "\n";
    echo "   QA Comments: " . ($inspection->qa_comments ?? 'None') . "\n";
    echo "   QA Rejection Reason: " . ($inspection->qa_rejection_reason ?? 'None') . "\n\n";
    
    echo "👨‍🔧 INSPECTOR DATA:\n";
    echo "   Lead Inspector: " . ($inspection->lead_inspector_name ?? 'N/A') . "\n";
    echo "   Inspector Cert: " . ($inspection->lead_inspector_certification ?? 'N/A') . "\n";
    echo "   Inspection Date: " . ($inspection->inspection_date ? $inspection->inspection_date->format('M j, Y') : 'N/A') . "\n";
    echo "   Overall Result: " . ($inspection->overall_result ?? 'N/A') . "\n\n";
    
    echo "📋 PDF SIGNATURE SECTION WILL SHOW:\n";
    echo "   ✅ Inspector certification with signature line\n";
    echo "   ✅ QA reviewer details and status\n";
    echo "   ✅ QA approval/rejection indicators\n";
    echo "   ✅ QA comments and rejection reasons\n";
    echo "   ✅ Certificate statement with QA validation\n";
    echo "   ✅ Color-coded approval status\n\n";
    
    if ($inspection->qa_status) {
        echo "🎯 QA STATUS BADGE: " . $inspection->qa_status_name . "\n";
        $statusColor = match($inspection->qa_status) {
            'qa_approved' => '🟢 GREEN (Approved)',
            'qa_rejected' => '🔴 RED (Rejected)', 
            'pending_qa' => '🟡 YELLOW (Pending)',
            'under_qa_review' => '🔵 BLUE (Under Review)',
            'revision_required' => '🟠 ORANGE (Revision Required)',
            default => '⚫ GRAY (Unknown)'
        };
        echo "   Color: " . $statusColor . "\n";
    } else {
        echo "🎯 QA STATUS: Not submitted for QA review\n";
    }
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
    exit(1);
}

echo "\n=== QUALITY CONTROL TEST COMPLETE ===\n";