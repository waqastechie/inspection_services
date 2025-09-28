<?php

namespace App\Notifications;

use App\Models\Inspection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InspectionApproved extends Notification implements ShouldQueue
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
            ->subject('Inspection Report Approved')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('An inspection report has been approved by Quality Assurance.')
            ->line('**Inspection Details:**')
            ->line('Report Number: ' . $this->inspection->inspection_number)
            ->line('Client: ' . $this->inspection->client_name)
            ->line('Submitted by: ' . $this->inspection->user->name)
            ->line('Approved by: ' . ($this->inspection->qaReviewer ? $this->inspection->qaReviewer->name : 'QA Team'))
            ->action('View Inspection', route('inspections.show', $this->inspection->id))
            ->line('The inspection report is now complete and ready for use.')
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
            'title' => 'Inspection Report Approved',
            'message' => 'Inspection #' . $this->inspection->inspection_number . ' has been approved by QA.',
            'inspection_id' => $this->inspection->id,
            'inspection_number' => $this->inspection->inspection_number,
            'client_name' => $this->inspection->client_name,
            'submitted_by' => $this->inspection->user->name,
            'approved_by' => $this->inspection->qaReviewer ? $this->inspection->qaReviewer->name : 'QA Team',
            'status' => $this->inspection->status,
            'action_url' => route('inspections.show', $this->inspection->id),
            'type' => 'inspection_approved'
        ];
    }
}
