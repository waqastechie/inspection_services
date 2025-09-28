<?php

namespace App\Http\Controllers;

use App\Models\Inspection;
use App\Models\InspectionComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Notifications\InspectionCommentAdded;

class InspectionCommentController extends Controller
{
    /**
     * Store a new comment
     */
    public function store(Request $request, Inspection $inspection)
    {
        $request->validate([
            'comment' => 'required|string|max:2000',
            'comment_type' => 'in:general,qa_review,revision_response,system'
        ]);

        DB::beginTransaction();
        try {
            $comment = $inspection->comments()->create([
                'user_id' => auth()->id(),
                'comment_type' => $request->comment_type ?? 'general',
                'comment' => $request->comment,
                'status' => 'active',
                'metadata' => [
                    'user_role' => auth()->user()->role,
                    'created_via' => 'web'
                ]
            ]);

            // Send notifications to relevant users
            $this->sendCommentNotifications($inspection, $comment);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Comment added successfully',
                'comment' => [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'comment_type' => $comment->comment_type,
                    'formatted_type' => $comment->formatted_type,
                    'type_color' => $comment->type_color,
                    'type_icon' => $comment->type_icon,
                    'user' => [
                        'name' => $comment->user->name,
                        'role' => $comment->user->role
                    ],
                    'created_at' => $comment->created_at->format('M j, Y g:i A'),
                    'created_at_human' => $comment->created_at->diffForHumans()
                ]
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Failed to add comment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing comment
     */
    public function update(Request $request, Inspection $inspection, InspectionComment $comment)
    {
        // Check if user can edit this comment
        if ($comment->user_id !== auth()->id() && !auth()->user()->hasRole(['super_admin', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'You can only edit your own comments'
            ], 403);
        }

        $request->validate([
            'comment' => 'required|string|max:2000'
        ]);

        try {
            $comment->update([
                'comment' => $request->comment,
                'metadata' => array_merge($comment->metadata ?? [], [
                    'edited_at' => now()->toISOString(),
                    'edited_by' => auth()->id()
                ])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comment updated successfully',
                'comment' => [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'user' => [
                        'name' => $comment->user->name,
                        'role' => $comment->user->role
                    ],
                    'created_at' => $comment->created_at->format('M j, Y g:i A'),
                    'updated_at' => $comment->updated_at->format('M j, Y g:i A'),
                    'is_edited' => true
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update comment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a comment (soft delete by setting status to 'deleted')
     */
    public function destroy(Inspection $inspection, InspectionComment $comment)
    {
        // Check if user can delete this comment
        if ($comment->user_id !== auth()->id() && !auth()->user()->hasRole(['super_admin', 'admin'])) {
            return response()->json([
                'success' => false,
                'message' => 'You can only delete your own comments'
            ], 403);
        }

        try {
            $comment->update([
                'status' => 'deleted',
                'metadata' => array_merge($comment->metadata ?? [], [
                    'deleted_at' => now()->toISOString(),
                    'deleted_by' => auth()->id()
                ])
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Comment deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete comment: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get comments for an inspection (AJAX endpoint)
     */
    public function index(Inspection $inspection)
    {
        try {
            $comments = $inspection->activeComments()->get()->map(function ($comment) {
                return [
                    'id' => $comment->id,
                    'comment' => $comment->comment,
                    'comment_type' => $comment->comment_type,
                    'formatted_type' => $comment->formatted_type,
                    'type_color' => $comment->type_color,
                    'type_icon' => $comment->type_icon,
                    'user' => [
                        'id' => $comment->user->id,
                        'name' => $comment->user->name,
                        'role' => $comment->user->role
                    ],
                    'created_at' => $comment->created_at->format('M j, Y g:i A'),
                    'created_at_human' => $comment->created_at->diffForHumans(),
                    'is_edited' => isset($comment->metadata['edited_at']),
                    'can_edit' => $comment->user_id === auth()->id() || auth()->user()->hasRole(['super_admin', 'admin']),
                    'can_delete' => $comment->user_id === auth()->id() || auth()->user()->hasRole(['super_admin', 'admin'])
                ];
            });

            return response()->json([
                'success' => true,
                'comments' => $comments
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to load comments: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Send notifications for new comments
     */
    private function sendCommentNotifications(Inspection $inspection, InspectionComment $comment)
    {
        $notifiableUsers = collect();
        $currentUser = auth()->user();

        // Get inspector/creator
        if ($inspection->creator && $inspection->creator->id !== $currentUser->id) {
            $notifiableUsers->push($inspection->creator);
        }

        // Get QA reviewer
        if ($inspection->qaReviewer && $inspection->qaReviewer->id !== $currentUser->id) {
            $notifiableUsers->push($inspection->qaReviewer);
        }

        // Get all admins and super admins
        $admins = \App\Models\User::whereIn('role', ['admin', 'super_admin'])
            ->where('id', '!=', $currentUser->id)
            ->get();
        $notifiableUsers = $notifiableUsers->merge($admins);

        // Get other users who have commented on this inspection
        $commenters = \App\Models\User::whereIn('id', 
            $inspection->comments()
                ->where('user_id', '!=', $currentUser->id)
                ->distinct()
                ->pluck('user_id')
        )->get();
        $notifiableUsers = $notifiableUsers->merge($commenters);

        // Remove duplicates
        $notifiableUsers = $notifiableUsers->unique('id');

        // Send notifications
        foreach ($notifiableUsers as $user) {
            try {
                $user->notify(new InspectionCommentAdded($inspection, $comment, $currentUser));
            } catch (\Exception $e) {
                // Log error but don't fail the comment creation
                logger()->error('Failed to send comment notification: ' . $e->getMessage());
            }
        }
    }
}