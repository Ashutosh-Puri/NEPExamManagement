<?php

namespace App\Livewire\Faculty\QuestionPaperBank;

use App\Models\Exam;
use Livewire\Component;
use App\Models\Paperset;
use App\Models\Academicyear;
use Livewire\WithFileUploads;
use App\Models\Papersubmission;
use Illuminate\Validation\Rule;
use App\Models\Questionpaperbank;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class QuestionPaperCell extends Component
{
    use WithFileUploads;
    protected $listeners = ['refreshChild'=>'render'];
    public $set;
    public $is_bank;
    public $subject_id;
    public $exam_id;
    public $questionbank=[];


    protected function rules()
    {   

        return ["questionbank.*" => ['required', 'file', 'mimes:pdf','max:2048']];
    }

    public function messages()
    {
        return [
            'questionbank.*.required' => 'The PDF file is required.',
            'questionbank.*.file' => 'The uploaded file is not valid.',
            'questionbank.*.mimes' => 'The file must be a PDF.',
            'questionbank.*.max' => 'The file may not be greater than 2048 kilobytes.',
        ];
    }


    public function reset_input()
    {
        $this->questionbank=[];
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }
 

    public function upload_question_paper_set_document()
    {   
        $this->validate();
        if(count($this->questionbank)==1)
        {
            if($this->exam_id)
            {   
                try 
                {
                    DB::beginTransaction();
                    
                    foreach ($this->questionbank as $set_id => $file) 
                    {
                        $papersubmission= Papersubmission::where('exam_id',$this->exam_id)->where('subject_id',$this->subject_id)->first();
                        if($papersubmission)
                        { 
                            $papersubmission->update(['noofsets'=>$papersubmission->noofsets+1,]);
                        }
                        else
                        {
                            $papersubmission= Papersubmission::create([
                                'exam_id'=>$this->exam_id,
                                'subject_id'=>$this->subject_id,
                                'noofsets'=>1,
                                'chairman_id'=>Auth::guard('faculty')->user()->id,
                                'status'=>0,         
                                'is_online'=>1         
                            ]);
                        }
                        $year=Academicyear::where('active',1)->first();
                        $papareset= Paperset::find($set_id);
                        if($papareset)
                        {
                            $questionbanks = Questionpaperbank::create([
                                'exam_id'=>$this->exam_id,
                                'papersubmission_id'=>$papersubmission->id,
                                'set_id'=>$set_id,
                                'file_name'=>'SET_'.$papareset->set_name.'_'.$papersubmission->subject->subject_name,
                                'chairman_id'=>Auth::guard('faculty')->user()->id,
                                'is_used'=>0,
                            ]);
                            if ($file !== null) 
                            {
                                $path = 'faculty/questionpaperbanks/'.str_replace(' ', '_', trim($year->year_name)).'/'.str_replace(' ', '_', trim(Auth::guard('faculty')->user()->faculty_name)).'/';
                                $fileName = str_replace(' ', '_', trim($questionbanks->file_name))."_".time() . '.' . $file->getClientOriginalExtension();
                                $file->storeAs($path, $fileName, 'public');
                                $questionbanks->update([ 'file_path'=>'storage/' . $path . $fileName,]);
                            }
                        }
                    }
                
                    DB::commit();

                    $this->questionbank=[];
                    $this->dispatch('alert',type:'success',message:'Question Paper Set Uploaded Successfully !!'  );
                } 
                catch (Exception $e) 
                {
                    DB::rollBack();
                    $this->dispatch('alert',type:'error',message:'Failed To Upload Question Paper Set !!'  );
                }
            }
        }else
        {
            $this->dispatch('alert',type:'info',message:'Please Upload At Lest One Question Paper Set !!'  );
        }
    }


    public function delete_question_paper_set_document($papersubmission_id ,$questionpaperbank_id)
    {   
        try 
        {
            DB::transaction(function () use ($papersubmission_id, $questionpaperbank_id) 
            {   
                $papersubmission=Papersubmission::withTrashed()->find($papersubmission_id);
                if($papersubmission) 
                {
                    if($papersubmission->questionbanks()->withTrashed()->count()===1)
                    {   
                        $questionbank =Questionpaperbank::withTrashed()->find($questionpaperbank_id);
                        if($questionbank)
                        {
                            if (isset($questionbank->file_path)) 
                            {
                                File::delete($questionbank->file_path);
                            }
                            
                            $questionbank->forceDelete();

                        }
                        
                        $papersubmission->forceDelete();

                        $this->dispatch('alert',type:'success',message:'Question Paper Set Deleted Successfully !!'  );
                    }else
                    {
                        $questionbank =Questionpaperbank::withTrashed()->find($questionpaperbank_id);
                        if($questionbank)
                        {

                            if (isset($questionbank->file_path)) 
                            {
                                File::delete($questionbank->file_path);
                            }
                                
                            $questionbank->forceDelete();

                            $papersubmission->update([
                                'noofsets'=>$papersubmission->noofsets-1
                            ]);

                            $this->dispatch('alert',type:'success',message:'Question Paper Set Deleted Successfully !!'  );
                        }
                    }
                }
            });

        } catch (Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:' Failed To Delete Question Paper Set !!'  );
        }
    }


    public function render()
    {
        $pap =Papersubmission::select('id')->where('subject_id', $this->subject_id)->where('exam_id', $this->exam_id)->first();
    
        if ($pap) 
        {
            $this->is_bank = $pap->questionbanks()->where('set_id', $this->set->id)->first();
        } else 
        {
          $this->is_bank = false;
        }

        return view('livewire.faculty.question-paper-bank.question-paper-cell');
    }
}
