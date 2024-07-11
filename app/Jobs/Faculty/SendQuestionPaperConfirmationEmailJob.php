<?php

namespace App\Jobs\Faculty;

use App\Models\Faculty;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Faculty\QuestionPaperBank\QuestionPaperBankConfirmationNotification;

class SendQuestionPaperConfirmationEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

   
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;

    }
    
    public function handle()
    {   
        try {
            $faculty = Faculty::findOrFail($this->data['faculty_id']);
            $faculty->notify(new QuestionPaperBankConfirmationNotification($this->data));
        } catch (\Exception $e) {
            Log::error('Error sending question paper confirmation email: ' . $e->getMessage());
        }
    }
}
