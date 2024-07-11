<?php

namespace App\Livewire\User\Examsupervision;

use App\Models\Exam;
use Livewire\Component;
use App\Models\Examsession;
use App\Models\Examsupervision;

class Sendemail extends Component
{
    public $allsession;
    public $session_date;

    public function mount()
    {
        $this->exam = Exam::where('status', 1)->first();
        $this->allsession=$this->exam->examsessions->unique('from_date', 'to_date');
    }

    public function updatedSessionDate($value)
    {
        $this->session_date = $value;
    }

    public function createsendmail()
    {
        
        // Fetch the active exam
        $exam = Exam::where('status', '1')->first();
        
        // Fetch all sessions matching the input date
        $allsessions = Examsession::where('from_date', $this->session_date)->get();
        // dd($allsessions);
        
        // Fetch all examsupervisions for the sessions
        $examsupervisions = Examsupervision::whereIn('examsession_id', $allsessions->pluck('id'))->get();
        // dd($examsupervisions);

        // Group by faculty_id and send emails
        foreach ($examsupervisions->where('emailstatus', '0')->groupBy('faculty_id') as $faculty_id => $supervisions) {
            $email = trim($supervisions->first()->faculty->email);

            $data = [
                'email' => $email,
                'examsupervisor' => $supervisions,
                'exam' => $exam,
            ];

            $pdf = PDF::loadView('supervision.supervisororder', $data)
                ->setPaper('a4')
                ->setOptions(['defaultFont' => 'sans-serif']);

            try {
                Mail::send('supervision.welcome', $data, function ($message) use ($data, $pdf) {
                    $message->to($data['email'])
                        ->cc(['exam.unit@sangamnercollege.edu.in'])
                        ->subject("Exam Order")
                        ->attachData($pdf->output(), 'examorder.pdf');
                });

                // Update email status to sent
                $supervisions->each(function ($item) {
                    $item->update(['emailstatus' => '1']);
                });
            } catch (\Exception $e) {
                // Handle email sending exception
                // Log the error or notify the admin
                \Log::error('Error sending email: ' . $e->getMessage());
            }
        }

        session()->flash('success', 'Mail Sent');
    }

    
    // public function createsendmail()
    // {
       
    //     ini_set('max_execution_time', 5000);
    //     ini_set('memory_limit', '4048M');
      
    //     $exam = Exam::where('status', '1')->first();
        
    //     $allsessions = Examsession::where('from_date', $this->session_date)->get();
        
    //     $examsupervisions = Examsupervision::whereIn('examsession_id', $allsessions->pluck('id'))->get();
    //     // dd($examsupervisions);

    //     // $email='sitaram.kawade@sangamnercollege.edu.in';
    //     // $data = compact('email','exam');
    //     // Mail::send('supervision.welcome',$data,function($message)use($data){
    //     //     $message->to($data["email"])
    //     //             ->subject("Exam Order");
    //     //  });


    //     foreach ($examsupervisions->where('emailstatus', '0')->groupBy('faculty_id') as $examsupervisor) {
    //         // dd($examsupervisor);

    //         //  $email='sitaram.kawade@sangamnercollege.edu.in';
    //         $email = trim($examsupervisor->first()->faculty->email);
    //         // dd($email);
    //         $data = compact('email', 'examsupervisor', 'exam');
    //         // dd($data);
    //         $pdf = PDF::loadView('supervision.supervisororder', $data)
    //             ->setPaper('a4')->setOptions(['defaultFont' => 'sans-serif']);;

    //         Mail::send('supervision.welcome', $data, function ($message) use ($data, $pdf) {
    //             $message->to($data["email"])
    //                 ->cc(['exam.unit@sangamnercollege.edu.in'])
    //                 ->subject("Exam Order")
    //                 ->attachData($pdf->output(), 'examorder.pdf');
    //         });

    //         $examsupervisor->each(function ($item) {
    //             $item->update(['emailstatus' => '1']);
    //         });
    //     }
    //     return back()->with('success', 'Mail Sent');
    // }


    public function render()
    {
        // $this->exam = Exam::where('status', 1)->first();
        // $this->allsession=$this->exam->examsessions->unique('from_date', 'to_date');

        return view('livewire.user.examsupervision.sendemail', ['allsession' => $this->allsession])->extends('layouts.user')->section('user');
    }
}
