<?php

namespace App\Mail;

use App\Models\Examorder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class CancelExamOrderMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct($details)
    {
        $this->details = $details;
    }

  
    public function build()
    {
        $e=Examorder::find($this->details['examorder_id']);
        $url=$this->details['url'];
          
        return $this->markdown('mail.cancelorder')
        ->subject('Your Appointment for the Exam Work')
        ->with([
            'examorder' => $e,
            'url'=>$url,
        ]);
    }
}
