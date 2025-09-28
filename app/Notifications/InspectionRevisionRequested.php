<?php

namespace App\Notifications;

use App\Models\Inspection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InspectionRevisionRequested extends Notification implements ShouldQueue
{
    use Queueable;

    protected $inspection;

    /**
     * Create a new notification instance.
     */
    public function __construct(Inspection $inspection)
    {
        $this->inspection = $inspection;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Inspection Report Revision Requested')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('Quality Assurance has requested revisions to an inspection report.')
            ->line('**Inspection Details:**')
            ->line('Report Number: ' . $this->inspection->inspection_number)
            ->line('Client: ' . $this->inspection->client_name)
            ->line('Submitted by: ' . $this->inspection->user->name)
            ->line('Requested by: ' . ($this->inspection->qaReviewer ? $this->inspection->qaReviewer->name : 'QA Team'))
            ->when($this->inspection->qa_comments, function ($mail) {
                return $mail->line('**Revision Comments:** ' . $this->inspection->qa_comments);
            })
            ->action('Review and Revise', route('inspections.edit', $this->inspection->id))
            ->line('Please make the requested revisions and resubmit the inspection report.')
            ->salutation('Best regards, Inspection Services System');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Inspection Revision Requested',
            'message' => 'QA has requested revisions for inspection #' . $this->inspection->inspection_number . '.',
            'inspection_id' => $this->inspection->id,
            'inspection_number' => $this->inspection->inspection_number,
            'client_name' => $this->inspection->client_name,
            'submitted_by' => $this->inspection->user->name,
            'requested_by' => $this->inspection->qaReviewer ? $this->inspection->qaReviewer->name : 'QA Team',
            'revision_comments' => $this->inspection->qa_comments,
            'status' => $this->inspection->status,
            'action_url' => route('inspections.edit', $this->inspection->id),
            'type' => 'inspection_revision_requested'
        ];
    }
}
