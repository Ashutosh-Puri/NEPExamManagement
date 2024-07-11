<?php

namespace App\Jobs\User;

use App\Mail\SendExamOrderMail;
use App\Models\Examorder;
use Illuminate\Bus\Queueable;
use App\Models\Exampatternclass;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendExamOrderEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;


    protected $mail_data;
    public function __construct($mail_data)
    {
        $this->mail_data = $mail_data;
       
    }

    public function handle(): void
    {   
        try 
        {
           
            $examorder = ExamOrder::find($this->mail_data['examorder_id']);

           
            Mail::to(trim($this->mail_data['email']))->cc(['exam.unit@sangamnercollege.edu.in', 'coeautonoumous@sangamnercollege.edu.in'])->send(new SendExamOrderMail($this->mail_data));

         
            $examorder->update(['email_status' => 1]);     
            
        }
        catch (\Exception $e) {
           
        }
    }   
}

