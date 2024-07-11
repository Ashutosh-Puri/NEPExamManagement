<?php

namespace App\Jobs\Student;

use App\Models\Student;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Student\PaymentNotification;


class StudentPaymentNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function handle()
    {   
        $student = Student::find($this->data['student_id']);
        
        if ($student) 
        {
            $student->notify(new PaymentNotification($this->data['payment_response']));
        }
    }
}
