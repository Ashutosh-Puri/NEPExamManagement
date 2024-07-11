<?php

namespace App\Mail\User;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use App\Models\Papersubmission;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Contracts\Queue\ShouldQueue;

class PaperSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public $details; 
    public  $papersubmission;

    public function __construct($details)
    {
        $this->details= $details; 
        $this->papersubmission= Papersubmission::find($details['papersubmission']);
    }

    public function build()
    {
        return $this->markdown('mail.user.papersubmission')
        ->subject('Acknowledgment regarding manuscript submission ')
        ->with([
            'details' => $this->details,
            'papersubmission'=>$this->papersubmission,
        ]);
        
    }
}
