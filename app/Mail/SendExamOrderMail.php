<?php

namespace App\Mail;

use App\Models\Examorder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;


class SendExamOrderMail extends Mailable
{
    use Queueable, SerializesModels;
    protected $mail_data; 


    public function __construct($mail_data)
    {
        $this->mail_data = $mail_data;
    }

    public function build()
    {
        $examorder=Examorder::find($this->mail_data['examorder_id']);
        $url=$this->mail_data['url'];
          
        return $this->markdown('mail.examorder')
        ->subject('Your Appointment for the Exam Work')
        ->with([
            'examorder' => $examorder,
            'url'=>$url,
        ]);
    }
  
}
