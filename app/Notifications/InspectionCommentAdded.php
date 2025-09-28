<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Inspection;
use App\Models\InspectionComment;
use App\Models\User;

class InspectionCommentAdded extends Notification
{
    use Queueable;

    protected $inspection;
    protected $comment;
    protected $commenter;

    /**
     * Create a new notification instance.
     */
    public function __construct(Inspection $inspection, InspectionComment $comment, User $commenter)
    {
        $this->inspection = $inspection;
        $this->comment = $comment;
        $this->commenter = $commenter;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $subject = "New Comment on Inspection #{$this->inspection->inspection_number}";
        
        return (new MailMessage)
            ->subject($subject)
            ->greeting('Hello!')
            ->line("A new comment has been added to inspection #{$this->inspection->inspection_number}.")
            ->line("**Comment by:** {$this->commenter->name} ({$this->commenter->role})")
            ->line("**Comment type:** {$this->comment->formatted_type}")
            ->line("**Comment:** {$this->comment->comment}")
            ->action('View Inspection', url("/inspections/{$this->inspection->id}"))
            ->line('Thank you for using our inspection management system!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'inspection_comment_added',
            'title' => 'New Comment Added',
            'message' => "{$this->commenter->name} added a comment to inspection #{$this->inspection->inspection_number}",
            'inspection_id' => $this->inspection->id,
            'inspection_number' => $this->inspection->inspection_number,
            'comment_id' => $this->comment->id,
            'comment_type' => $this->comment->comment_type,
            'comment_excerpt' => \Str::limit($this->comment->comment, 100),
            'commenter' => [
                'id' => $this->commenter->id,
                'name' => $this->commenter->name,
                'role' => $this->commenter->role
            ],
            'action_url' => "/inspections/{$this->inspection->id}#comment-{$this->comment->id}",
            'created_at' => now()->toISOString()
        ];
    }

    /**
     * Get the notification's database type.
     */
    public function databaseType(object $notifiable): string
    {
        return 'inspection_comment_added';
    }
}