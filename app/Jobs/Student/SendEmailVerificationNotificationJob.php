<?php

namespace App\Jobs\Student;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Student\StudentRegisterMailNotification;

class SendEmailVerificationNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected  $student;

    public function __construct( $student)
    {
        $this->student= $student;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->student->notify(new StudentRegisterMailNotification);

    }
}
