<?php

namespace App\Notifications;

use App\Models\Backup;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class BackupNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $backup;
    protected $message;
    protected $success;

    public function __construct(Backup $backup, string $message, bool $success)
    {
        $this->backup = $backup;
        $this->message = $message;
        $this->success = $success;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject($this->success ? 'Backup Restore Completed' : 'Backup Restore Failed')
                    ->line($this->message)
                    ->action('View Backup', route('backups.show', $this->backup))
                    ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'backup_id' => $this->backup->id,
            'message' => $this->message,
            'success' => $this->success,
            'link' => route('backups.show', $this->backup)
        ];
    }
}