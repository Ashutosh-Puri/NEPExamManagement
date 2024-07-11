<?php

namespace App\Jobs\Faculty;

use App\Models\Faculty;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Notifications\Faculty\QuestionPaperPasswordNotification;

class SendQuestionPaperPasswordEmailJob implements ShouldQueue
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
            $faculty_name=$faculty->faculty_name;
            $faculty->notify(new QuestionPaperPasswordNotification($faculty_name, $this->data['document_name'], $this->data['password']));
        } catch (\Exception $e) {
            Log::error('Error sending question paper password email: ' . $e->getMessage());
        }
    }
}
