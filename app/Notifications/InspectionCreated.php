<?php

namespace App\Notifications;

use App\Models\Inspection;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InspectionCreated extends Notification implements ShouldQueue
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
            ->subject('New Inspection Report Submitted')
            ->greeting('Hello ' . $notifiable->name . ',')
            ->line('A new inspection report has been submitted and requires your review.')
            ->line('**Inspection Details:**')
            ->line('Report Number: ' . $this->inspection->inspection_number)
            ->line('Client: ' . $this->inspection->client_name)
            ->line('Submitted by: ' . $this->inspection->user->name)
            ->line('Status: ' . ucfirst(str_replace('_', ' ', $this->inspection->status)))
            ->action('Review Inspection', route('inspections.show', $this->inspection->id))
            ->line('Please review the inspection report at your earliest convenience.')
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
            'title' => 'New Inspection Report Submitted',
            'message' => 'Inspection #' . $this->inspection->inspection_number . ' by ' . $this->inspection->user->name . ' requires review.',
            'inspection_id' => $this->inspection->id,
            'inspection_number' => $this->inspection->inspection_number,
            'client_name' => $this->inspection->client_name,
            'submitted_by' => $this->inspection->user->name,
            'status' => $this->inspection->status,
            'action_url' => route('inspections.show', $this->inspection->id),
            'type' => 'inspection_created'
        ];
    }
}
