<?php

namespace App\Jobs\Faculty;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Faculty\FacultyRegisterMailNotification;

class SendEmailVerificationNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected  $faculty;

    public function __construct( $faculty)
    {
        $this->faculty= $faculty;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $this->faculty->notify(new FacultyRegisterMailNotification);
    }
}
