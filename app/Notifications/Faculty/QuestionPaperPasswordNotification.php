<?php

namespace App\Notifications\Faculty;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class QuestionPaperPasswordNotification extends Notification
{
    use Queueable;

    protected $faculty_name;
    protected $document_name;
    protected $password;


    public function __construct($faculty_name, $document_name, $password)
    {
        $this->faculty_name = $faculty_name;
        $this->document_name = $document_name;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Question Paper PDF Password')
                    ->line('Dear ' . $this->faculty_name. ',')
                    ->line('The password to access the question paper PDF file "' . $this->document_name . '" is:')
                    ->line($this->password)
                    ->line('Please keep this password secure.')
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
