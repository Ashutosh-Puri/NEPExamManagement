<?php

namespace App\Livewire\User\Ratehead;

use Excel;
use Livewire\Component;
use App\Models\Ratehead;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\Ratehead\ExportRatehead;

class AllRateHead extends Component
{   
    ## By Ashutosh
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;
    
    public $headname;
    public $type;
    public $noofcredit;
    public $course_type;
    public $amount;
    public $status; 
    #[Locked] 
    public $delete_id;
    #[Locked] 
    public $mode='all';
    #[Locked] 
    public $edit_id;

    protected function rules()
    {
        return [
        'headname' => ['required','string'],
        'type' => ['required','string','max:2'],      
        'noofcredit' => ['required','numeric','max:4'],      
        'course_type' => ['required','string','max:20'],      
        'amount' => ['required','numeric','min:0' , 'max:99999999.99'],      
        ];
    }

    public function messages()
    {   
        $messages = [
            'headname.required' => 'The headname field is required.',
            'headname.string' => 'The headname must be a string.',
            'type.required' => 'The type field is required.',
            'type.string' => 'The type must be a string.',
            'type.max' => 'The type may not be greater than :max characters.',
            'noofcredit.required' => 'The noofcredit field is required.',
            'noofcredit.numeric' => 'The noofcredit must be a number.',
            'noofcredit.max' => 'The noofcredit may not be greater than :max.',
            'course_type.required' => 'The course type field is required.',
            'course_type.string' => 'The course type must be a string.',
            'course_type.max' => 'The course type may not be greater than :max characters.',
            'amount.required' => 'The amount field is required.',
            'amount.numeric' => 'The amount must be a number.',
            'amount.min' => 'The amount must be at least :min.',
            'amount.max' => 'The amount may not be greater than :max.',
            ];
        return $messages;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetinput()
    {
        $this->reset([
            'edit_id',
            'headname',
            'type',
            'noofcredit',
            'course_type',
            'amount',
            'status',
        ]);

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

    public function updatedSearch()
    {
        $this->resetPage();
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
    #[Renderless]
    public function export()
    {   
        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Rate_head_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportRatehead($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportRatehead($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportRatehead($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Rate Head Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Rate Head !!');
        }

    }


    public function add()
    { 
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $ratehead =  new Ratehead;
            $ratehead->create([
                'headname' => $this->headname,
                'type' => $this->type,
                'noofcredit' => $this->noofcredit,
                'course_type' => $this->course_type,
                'amount' => $this->amount,
                'status' => $this->status,
            ]);
            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Rate Head Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Rate Head !!');
        }
    }


    public function edit(Ratehead $ratehead)
    {   
        $this->resetinput();
        $this->edit_id=$ratehead->id;
        $this->headname=$ratehead->headname;
        $this->type=$ratehead->type;   
        $this->noofcredit=$ratehead->noofcredit;   
        $this->course_type=$ratehead->course_type;   
        $this->amount=$ratehead->amount;   
        $this->status=$ratehead->status;   
        $this->mode='edit';
    }

    public function update(Ratehead $ratehead)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {
                    
            $ratehead->update([
                'headname' => $this->headname,
                'type' => $this->type,
                'noofcredit' => $this->noofcredit,
                'course_type' => $this->course_type,
                'amount' => $this->amount,
                'status' => $this->status,
            ]);

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Rate Head Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Rate Head !!');
        }
    }
 
    public function update_status(Ratehead $ratehead)
    {
        DB::beginTransaction();

        try 
        {   
            if($ratehead->status)
            {
                $ratehead->status=0;
            }
            else
            {
                $ratehead->status=1;
            }
            $ratehead->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }


    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function delete(Ratehead  $ratehead)
    {  
        DB::beginTransaction();

        try 
        {
            $ratehead->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Rate Head Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Rate Head !!');
        }
    }


    public function restore($id)
    {   
       
        DB::beginTransaction();

        try
        {
            $ratehead = Ratehead::withTrashed()->findOrFail($id);

            $ratehead->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Rate Head Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Rate Head !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        { 
            $ratehead = Ratehead::withTrashed()->find($this->delete_id);
            $ratehead->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Rate Head Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Rate Head !!');
            }
        }
    }

    
    public function render()
    {
        $rateheads=Ratehead::when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.ratehead.all-rate-head',compact('rateheads'))->extends('layouts.user')->section('user');
    }
}
