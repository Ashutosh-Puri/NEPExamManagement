<?php

namespace App\Livewire\User\Credit;

use Excel;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subjectcredit;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\Credit\ExportCredit;

class AllCredit extends Component
{   
    ## By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10;
    public $search='';
    public $sortColumn="credit";
    public $sortColumnBy="ASC";
    public $ext;  
    public $credit;  
    public $marks;  
    public $passing;  
    public $steps=1;
    public $current_step=1;

    #[Locked] 
    public $delete_id;
    #[Locked] 
    public $credit_id;
    #[Locked] 
    public $mode='all';

    
    protected function rules()
    {
        return [
        'credit' => ['required','numeric','between:0.00,9999.99'],
        'marks' =>  ['required','numeric','between:0.00,9999.99'],
        'passing' => ['required','max:5'],
        ];
    }

    public function messages()
    {   
        $messages = [
            'credit.required' => 'The credit field is required.',
            'credit.numeric' => 'The credit must be a number.',
            'credit.between' => 'The credit must be between 0.00 and 9999.99.',
            'marks.required' => 'The marks field is required.',
            'marks.numeric' => 'The marks must be a number.',
            'marks.between' => 'The marks must be between 0.00 and 9999.99.',
            'passing.required' => 'The passing field is required.',
            'passing.max' => 'The passing field may not be greater than 5 characters.',   
        ];
        return $messages;
    }

    public function resetinput()
    {
        $this->reset([
            'credit',
            'marks',
            'passing',
            'credit_id',
        ]);
    }

    public function updated($property)
    {
        $this->validateOnly($property);
    }

    public function setmode($mode)
    {
        if($mode=='add')
        {
            $this->resetinput();
        }
        $this->mode=$mode;
        $this->resetValidation();
    }

    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $credits = new Subjectcredit;
            $credits->credit = $this->credit;
            $credits->marks = $this->marks;
            $credits->passing = $this->passing;
            $credits->save();

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Credit Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Credit !!');
        }
    }

    public function edit(Subjectcredit $credits){

        if ($credits) {
            $this->resetinput();
            $this->credit_id=$credits->id;
            $this->credit = $credits->credit;     
            $this->marks = $credits->marks;
            $this->passing = $credits->passing ;
            $this->mode='edit';
        }
    }

    public function update(Subjectcredit  $credits)
    {   
    
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $credits->credit= $this->credit;
            $credits->marks= $this->marks;
            $credits->passing= $this->passing;
            $credits->update();
            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Credit Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Credit !!');
        }
    }

    public function deleteconfirmation($credit_id)
    {
        $this->delete_id=$credit_id;
        $this->dispatch('delete-confirmation');
    }

    public function delete(Subjectcredit  $credits)
    {   
        
        DB::beginTransaction();

        try 
        {
            $credits->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Credit Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Credit !!');
        }
    }

    public function restore($credit_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $credit = Subjectcredit::withTrashed()->findOrFail($credit_id);
            $credit->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Credit Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Credit !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        {
            $credits = Subjectcredit::withTrashed()->find($this->delete_id);
            $credits->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Credit Deleted Successfully !!');

        } 
        catch (\Illuminate\Database\QueryException $e) 
        {
            DB::rollBack();

            if ($e->errorInfo[1] == 1451) 
            {
                $this->dispatch('alert',type:'info',message:'This Record Is Associated With Another Data. You Cannot Delete It !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Credit !!');
            }
        }
    }

    public function sort_column($column)
    {
        if( $this->sortColumn === $column)
        {
            $this->sortColumnBy=($this->sortColumnBy=="ASC")?"DESC":"ASC";
            return;
        }
        $this->sortColumn=$column;
        $this->sortColumnBy=="ASC";
    }

    #[Renderless]
    public function export()
    {   
        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Credit_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportCredit($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportCredit($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportCredit($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Credit Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Credit !!');
        }

    }


    public function render()
    {
        $SubCredits=Subjectcredit::when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.credit.all-credit',compact('SubCredits'))->extends('layouts.user')->section('user');
    }
}
