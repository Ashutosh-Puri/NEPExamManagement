<?php

namespace App\Livewire\User\ExamForm;

use Livewire\Component;
use App\Models\Examformmaster;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class DeleteExamFormBeforeInward extends Component
{   
    # By Ashutosh
    public $examformmasterid;

    protected function rules()
    {
        return [
            'examformmasterid' => ['required', 'integer',Rule::exists('examformmasters', 'id')],
        ];
    }

    public function messages()
    {     
        return  [
            'examformmasterid.required' => 'The Application ID field is required.',
            'examformmasterid.integer' => 'The Application ID must be an integer.',
            'examformmasterid.exists' => 'The Application ID does not exist.',
        ];
    }


    public function delete()
    {   
        DB::beginTransaction();

        try 
        {
            $examformmaster =Examformmaster::find($this->examformmasterid);
            $examformmaster->printstatus=0;
            $examformmaster->update();
            $this->examformmasterid=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Form Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Exam Form !!');
        }
    }

    public function render()
    {
        return view('livewire.user.exam-form.delete-exam-form-before-inward')->extends('layouts.user')->section('user');
    }
}
