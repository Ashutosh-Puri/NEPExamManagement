<?php

namespace App\Livewire\User\Cap;

use Excel;
use App\Models\Exam;
use App\Models\College;
use Livewire\Component;
use App\Models\Capmaster;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use App\Exports\User\Cap\CapExport;
use Livewire\Attributes\Renderless;
use Illuminate\Support\Facades\Auth;

class AllCap extends Component
{   
    # By Ashutosh
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    #[Locked] 
    public $delete_id;
    #[Locked]
    public $mode='all';
    public $perPage=10;
    public $search='';
    public $sortColumn="exam_id";
    public $sortColumnBy="DESC";
    public $ext;

    public $cap_name;
    public $place;
    public $exam_id;
    public $college_id ;
    public $status;

    #[Locked]
    public $exams=[];
    #[Locked]
    public $colleges=[];
    #[Locked] 
    public $edit_id;


    protected function rules()
    {
        return [
            'college_id' => ['required', 'integer', Rule::exists('colleges', 'id')],
            'exam_id' => ['required', 'integer', Rule::exists('exams', 'id')],
            'cap_name' => ['required', 'string','max:255',Rule::unique('capmasters', 'cap_name')->ignore($this->edit_id, 'id')],
            'place' => ['nullable', 'string','max:255'],
        ];
    }

    public function messages()
    {   
        $messages = [
            'college_id.required' => 'The College  field is required.',
            'college_id.integer' => 'The College  must be a integer value.',
            'college_id.exists' => 'The selected College  is invalid.',
            
            'exam_id.required' => 'The Exam field is required.',
            'exam_id.integer' => 'The Exam must be a integer value.',
            'exam_id.exists' => 'The selected Exam is invalid.',
            
            'cap_name.required' => 'The CAP Name field is required.',
            'cap_name.string' => 'The CAP Name must be a string.',
            'cap_name.max' => 'The CAP Name may not be greater than :max characters.',
            'cap_name.unique' => 'The CAP Name has already been taken.',
            
            'place.required' => 'The Place field is required.',
            'place.string' => 'The Place must be a string.',
            'place.max' => 'The Place may not be greater than :max characters.',
        ];
        
        return $messages;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetinput()
    {
        $this->reset(
            [
                'edit_id',
                'cap_name',
                'place',
                'exam_id',
                'status',
                'college_id',
            ]
        );
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

            $filename="Cap_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new CapExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new CapExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new CapExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Cap Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Cap !!');
        }
    }


    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $cap =  new Capmaster;
            $cap->create([
                'cap_name' => $this->cap_name,
                'place' => $this->place,
                'status' => $this->status == true ? 0 : 1,
                'college_id' => $this->college_id,
                'exam_id' => $this->exam_id,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Cap Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Cap !!');
        }
    }


    public function edit(Capmaster $cap)
    {   
        $this->resetinput();
        $this->edit_id=$cap->id;
        $this->cap_name= $cap->cap_name;
        $this->status=$cap->status==1?0:true;
        $this->place=$cap->place;
        $this->college_id=$cap->college_id;
        $this->exam_id=$cap->exam_id;
        $this->mode='edit';
    }

    public function update(Capmaster $cap)
    {
        $this->validate();
        
        DB::beginTransaction();

        try 
        {   
            $cap->update([
                'cap_name' => $this->cap_name,
                'place' => $this->place,
                'status' => $this->status == true ? 0 : 1,
                'college_id' => $this->college_id,
                'exam_id' => $this->exam_id,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Cap Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Cap !!');
        }
    }


    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }


    public function delete(Capmaster  $cap)
    {  
        DB::beginTransaction();

        try
        {   
            $cap->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Cap Soft Deleted Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Cap !!');
        }
    }
 
    
    public function restore($id)
    {   
        DB::beginTransaction();

        try
        {   
            $cap = Capmaster::withTrashed()->find($id);
            $cap->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Cap Restored Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Cap !!');
        }
    }

  

    public function forcedelete()
    {   
        DB::beginTransaction();
        try 
        {
            $cap = Capmaster::withTrashed()->find($this->delete_id);
            $cap->forceDelete();
            $this->delete_id=null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Cap Deleted Successfully !!');
            
        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();
            
            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Cap !!');
            }
        }
    }


    public function updatestatus(Capmaster $cap)
    {
        DB::beginTransaction();

        try 
        {   
            if($cap->status)
            {
                $cap->status=0;
            }
            else
            {
                $cap->status=1;
            }
            $cap->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }

    public function render()
    {   

        if($this->mode!=='all')
        {
            $this->colleges=College::where('status',1)->pluck('college_name','id');
            $this->exams =Exam::where('status',1)->pluck('exam_name','id');
        }

        $caps=Capmaster::with('college:college_name,id','exam:exam_name,id')->where('college_id',Auth::guard('user')->user()->college_id)->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.cap.all-cap',compact('caps'))->extends('layouts.user')->section('user');
    }

}
