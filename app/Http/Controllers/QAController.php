<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class QAController extends Controller
{
    /**
     * Display QA dashboard with pending inspections
     */
    public function dashboard()
    {
        // Check if user has QA permissions
        if (!auth()->user()->canApproveInspections()) {
            abort(403, 'Access denied. Quality Assurance permissions required.');
        }

        // Get various inspection counts for dashboard metrics
        $pendingQA = Inspection::where('qa_status', 'pending_qa')->count();
        $underReview = Inspection::where('qa_status', 'under_qa_review')->count();
        $approved = Inspection::where('qa_status', 'qa_approved')->count();
        $rejected = Inspection::where('qa_status', 'qa_rejected')->count();
        $requiresRevision = Inspection::where('qa_status', 'revision_required')->count();

        // Get recent inspections for different QA statuses
        $pendingInspections = Inspection::with(['client', 'qaReviewer'])
            ->where('qa_status', 'pending_qa')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        $underReviewInspections = Inspection::with(['client', 'qaReviewer'])
            ->where('qa_status', 'under_qa_review')
            ->where('qa_reviewer_id', auth()->id())
            ->orderBy('updated_at', 'desc')
            ->limit(10)
            ->get();

        $recentlyReviewed = Inspection::with(['client', 'qaReviewer'])
            ->whereIn('qa_status', ['qa_approved', 'qa_rejected', 'revision_required'])
            ->orderBy('qa_reviewed_at', 'desc')
            ->limit(10)
            ->get();

        return view('qa.dashboard', compact(
            'pendingQA',
            'underReview',
            'approved',
            'rejected',
            'requiresRevision',
            'pendingInspections',
            'underReviewInspections',
            'recentlyReviewed'
        ));
    }

    /**
     * Show list of inspections pending QA review
     */
    public function pending()
    {
        if (!auth()->user()->canApproveInspections()) {
            abort(403, 'Access denied. Quality Assurance permissions required.');
        }

        $inspections = Inspection::with(['client', 'qaReviewer'])
            ->where('qa_status', 'pending_qa')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('qa.pending', compact('inspections'));
    }

    /**
     * Show list of inspections under QA review
     */
    public function underReview()
    {
        if (!auth()->user()->canApproveInspections()) {
            abort(403, 'Access denied. Quality Assurance permissions required.');
        }

        $inspections = Inspection::with(['client', 'qaReviewer'])
            ->where('qa_status', 'under_qa_review')
            ->when(!auth()->user()->isSuperAdmin(), function ($query) {
                $query->where('qa_reviewer_id', auth()->id());
            })
            ->orderBy('updated_at', 'desc')
            ->paginate(20);

        return view('qa.under-review', compact('inspections'));
    }

    /**
     * Show QA review form for a specific inspection
     */
    public function review(Inspection $inspection)
    {
        if (!auth()->user()->canApproveInspections()) {
            abort(403, 'Access denied. Quality Assurance permissions required.');
        }

        // If inspection is pending QA, assign current user as reviewer
        if ($inspection->qa_status === 'pending_qa') {
            $inspection->startQAReview(auth()->user());
        }

        // Check if current user can review this inspection
        if ($inspection->qa_reviewer_id && 
            $inspection->qa_reviewer_id !== auth()->id() && 
            !auth()->user()->isSuperAdmin()) {
            abort(403, 'This inspection is already being reviewed by another QA member.');
        }

        $inspection->load([
            'client',
            'qaReviewer',
            'liftingExamination',
            'mpiInspection',
            'personnelAssignments.personnel',
            'equipmentAssignments.equipment',
            'consumableAssignments.consumable'
        ]);

        return view('qa.review', compact('inspection'));
    }

    /**
     * Approve inspection after QA review
     */
    public function approve(Request $request, Inspection $inspection)
    {
        if (!auth()->user()->canApproveInspections()) {
            abort(403, 'Access denied. Quality Assurance permissions required.');
        }

        $request->validate([
            'qa_comments' => 'nullable|string|max:2000'
        ]);

        $inspection->approveQA(auth()->user(), $request->qa_comments);

        // Send notifications
        $this->sendInspectionApprovedNotifications($inspection);

        return redirect()
            ->route('qa.dashboard')
            ->with('success', "Inspection {$inspection->inspection_number} has been approved.");
    }

    /**
     * Reject inspection after QA review
     */
    public function reject(Request $request, Inspection $inspection)
    {
        if (!auth()->user()->canApproveInspections()) {
            abort(403, 'Access denied. Quality Assurance permissions required.');
        }

        $request->validate([
            'qa_rejection_reason' => 'required|string|max:1000',
            'qa_comments' => 'nullable|string|max:2000'
        ]);

        $inspection->rejectQA(
            auth()->user(),
            $request->qa_rejection_reason,
            $request->qa_comments
        );

        // Send notifications
        $this->sendInspectionRejectedNotifications($inspection);

        return redirect()
            ->route('qa.dashboard')
            ->with('warning', "Inspection {$inspection->inspection_number} has been rejected.");
    }

    /**
     * Request revision for inspection
     */
    public function requestRevision(Request $request, Inspection $inspection)
    {
        if (!auth()->user()->canApproveInspections()) {
            abort(403, 'Access denied. Quality Assurance permissions required.');
        }

        $request->validate([
            'qa_rejection_reason' => 'required|string|max:1000',
            'qa_comments' => 'nullable|string|max:2000'
        ]);

        $inspection->requireRevision(
            auth()->user(),
            $request->qa_rejection_reason,
            $request->qa_comments
        );

        // Send notifications
        $this->sendInspectionRevisionRequestedNotifications($inspection);

        return redirect()
            ->route('qa.dashboard')
            ->with('info', "Revision requested for inspection {$inspection->inspection_number}.");
    }

    /**
     * Send notifications when an inspection is approved
     */
    private function sendInspectionApprovedNotifications(Inspection $inspection)
    {
        // Notify the inspector who created the report
        if ($inspection->user) {
            $inspection->user->notify(new \App\Notifications\InspectionApproved($inspection));
        }

        // Notify admin and super admin users
        $adminRecipients = User::whereIn('role', ['admin', 'super_admin'])
            ->where('is_active', true)
            ->get();

        foreach ($adminRecipients as $recipient) {
            $recipient->notify(new \App\Notifications\InspectionApproved($inspection));
        }
    }

    /**
     * Send notifications when an inspection is rejected
     */
    private function sendInspectionRejectedNotifications(Inspection $inspection)
    {
        // Notify the inspector who created the report
        if ($inspection->user) {
            $inspection->user->notify(new \App\Notifications\InspectionRejected($inspection));
        }

        // Notify admin and super admin users
        $adminRecipients = User::whereIn('role', ['admin', 'super_admin'])
            ->where('is_active', true)
            ->get();

        foreach ($adminRecipients as $recipient) {
            $recipient->notify(new \App\Notifications\InspectionRejected($inspection));
        }
    }

    /**
     * Send notifications when revision is requested for an inspection
     */
    private function sendInspectionRevisionRequestedNotifications(Inspection $inspection)
    {
        // Notify the inspector who created the report
        if ($inspection->user) {
            $inspection->user->notify(new \App\Notifications\InspectionRevisionRequested($inspection));
        }

        // Notify admin and super admin users
        $adminRecipients = User::whereIn('role', ['admin', 'super_admin'])
            ->where('is_active', true)
            ->get();

        foreach ($adminRecipients as $recipient) {
            $recipient->notify(new \App\Notifications\InspectionRevisionRequested($inspection));
        }
    }
    public function history()
    {
        if (!auth()->user()->canApproveInspections()) {
            abort(403, 'Access denied. Quality Assurance permissions required.');
        }

        $inspections = Inspection::with(['client', 'qaReviewer'])
            ->whereIn('qa_status', ['qa_approved', 'qa_rejected', 'revision_required'])
            ->when(!auth()->user()->isSuperAdmin(), function ($query) {
                $query->where('qa_reviewer_id', auth()->id());
            })
            ->orderBy('qa_reviewed_at', 'desc')
            ->paginate(20);

        return view('qa.history', compact('inspections'));
    }
}
