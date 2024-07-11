<?php

namespace App\Livewire\User\StrongRoom;

use Carbon\Carbon;
use App\Models\Exam;
use App\Models\College;
use App\Models\Setting;
use Livewire\Component;
use App\Models\Paperset;
use App\Models\Examorder;
use App\Models\Exampanel;
use Livewire\WithPagination;
use App\Models\Examtimetable;
use App\Models\Subjectbucket;
use App\Models\Timetableslot;
use App\Models\Papersubmission;
use Illuminate\Validation\Rule;
use App\Models\Questionpaperbank;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class StrongRoom extends Component
{   
    # By Ashutosh
    use WithPagination;

    public $perPage=10;
    
    public $question_bank=[];
    public $set_id;
    public $exam;
    public $exam_patternclass_ids=[];
    public $subject_ids=[];
    public $pappersets=[];
    protected function rules()
    {
        return [
            'set_id' => ['required', 'integer', Rule::exists('papersets', 'id')],
        ];
    }

    public function messages()
    {   
        $messages = [
            'set_id.required' => 'Set is required.',
            'set_id.integer' => 'Set must be a integer value.',
            'set_id.exists' => 'The selected Set does not exist.',
        ];
        
        return $messages;
    }

   
    public function approve_papaer_set()
    {   
        if(empty($this->question_bank))
        {
            $this->dispatch('alert',type:'info',message:'Please Select Question Paper Set  !!'  );
            return false;
        }

        DB::beginTransaction();

        try 
        {

            if($this->exam)
            {
                Questionpaperbank::whereIn('id', array_keys(array_filter($this->question_bank)))->update(['exam_date'=>date('Y-m-d'),'is_used' => 1]);
                DB::commit();
                $this->dispatch('alert',type:'success',message:'Question Paper Set Selected Successfully !!'  );
            }
          
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('alert',type:'error',message:'Failed To Select Question Paper Set  !!'  );
        }
    }

    public function mount()
    {   
        $this->exam = Exam::where('status', 1)->first();
        $this->exam_patternclass_ids = $this->exam->exampatternclasses()->where('launch_status', 1)->pluck('id');
        $exampanel_ids= Examorder::whereIn('exam_patternclass_id',$this->exam_patternclass_ids)->where('email_status',1)->pluck('exampanel_id');
        $this->subject_ids =Exampanel::whereIn('id',$exampanel_ids)->where('examorderpost_id',1)->pluck('subject_id');
        $this->pappersets = Paperset::get();
    }


    public function render()
    {   

        $intervalInMinutes =120;

        $college=College::where('is_default',1)->first();

        if($college)
        {
            $setting=Setting::where('college_id',$college->id)->first();
            if ($setting) 
            {
            
               $intervalInMinutes =$setting->exam_time_interval;
            }
        }

        $currentDateTime = Carbon::now();
      
        $startTime = \DateTime::createFromFormat('H:i:s',  $currentDateTime->toTimeString())->format('H:i:s');
        $endTime = \DateTime::createFromFormat('H:i:s', $currentDateTime->addMinutes($intervalInMinutes)->toTimeString())->format('H:i:s');

        $papersubmissions = collect();

        if ($this->exam) 
        {   
            $timeslot_ids=Timetableslot::whereBetween('start_time',[$startTime, $endTime])->pluck('id');
            $subject_ids_filtered = Examtimetable::whereIn('timeslot_id',$timeslot_ids)->whereIn('subject_id',$this->subject_ids)->where('status',1)->whereIn('exam_patternclasses_id', $this->exam_patternclass_ids)->whereDate('examdate',date('Y-m-d'))->pluck('subject_id');
            $papersubmissions = Papersubmission::where('is_confirmed', 1)->whereIn('subject_id', $subject_ids_filtered)->paginate($this->perPage);
        }

        return view('livewire.user.strong-room.strong-room', compact('papersubmissions'))->extends('layouts.user')->section('user');
    }

}
