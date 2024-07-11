<?php

namespace App\Jobs\User;

use PDF;
use App\Models\Exam;
use App\Models\Unfairmeans;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendUnfairmeanEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $unfaircases;
    protected $exam;

    public function __construct($unfaircases, $exam)
    {
        $this->unfaircases = $unfaircases;
        $this->exam = $exam;

    }

    public function handle()
    {
        $name = $this->unfaircases->first()->student->student_name;
        $email = $this->unfaircases->first()->student->email;

        $data = compact('email', 'name');

        $pdf = PDF::loadView('pdf.user.unfairmeans.unfairmeans_pdf', ['exam'=>$this->exam,'unfaircases'=>$this->unfaircases])->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);
      
        Mail::send('livewire.user.unfairmean.emailunfair', $data, function ($message) use ($data, $pdf) {

            $message->to($data["email"])
            ->cc(['exam.unit@sangamnercollege.edu.in'])
            ->subject("Very Important : Show Cause Notice (under clause 10(i) of Ordinance 09)in respect of Unfair means ")
            ->attachData($pdf->output(), 'UnfairMeans.pdf');
        });    
    }
}
