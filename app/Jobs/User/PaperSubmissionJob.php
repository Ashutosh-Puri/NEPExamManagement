<?php

namespace App\Jobs\User;

use Illuminate\Bus\Queueable;
use App\Mail\User\PaperSubmitted;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PaperSubmissionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   
    public function __construct( $papersubmission)
    {
        $this->papersubmission = $papersubmission;
    }

    public function handle(): void
    {
         
        Mail::to(trim($this->facultydata->faculty->email))
        ->cc(['exam.unit@sangamnercollege.edu.in'])
        ->send(new PaperSubmitted($papersubmission));
    }
}
