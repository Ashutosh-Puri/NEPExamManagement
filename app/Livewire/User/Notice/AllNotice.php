<?php

namespace App\Livewire\User\Notice;

use Excel;
use App\Models\User;
use App\Models\Notice;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\Notice\NoticeExport;

class AllNotice extends Component
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
    public $sortColumn="title";
    public $sortColumnBy="ASC";
    public $ext;

    public $user_id;
    public $type;
    public $start_date;
    public $end_date;
    public $title;
    public $description;
    public $is_active;

    #[Locked] 
    public $edit_id;


    protected function rules()
    {
        return [
            'type' => ['required', 'integer','between:0,4'],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
            'title' => ['required', 'string','max:100'],
            'description' => ['nullable', 'string','max:1000'],
 

        ];
    }

    public function messages()
    {   
        $messages = [
            'type.nullable' => 'The Notice Type Field must be either null or have a valid integer value.',
            'type.between' => 'The Notice Type field must be between :min and :max .',
            'start_date.nullable' => 'The Start Date field must be either null or have a valid date format.',
            'end_date.nullable' => 'The End Date field must be either null or have a valid date format.',
            'title.required' => 'The Title field is required.',
            'title.string' => 'The Title must be a valid string.',
            'title.max' => 'The Title must not exceed :max characters.',
            'description.nullable' => 'The description field must be either null or have a valid string value.',
            'description.string' => 'The description must be a valid string.',
            'description.max' => 'The description must not exceed :max characters.',
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
                'user_id',
                'type',
                'is_active',
                'start_date',
                'end_date',
                'title',
                'description'
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

            $filename="Notice_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new NoticeExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new NoticeExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new NoticeExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Notice Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Notice !!');
        }
    }

    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
           
            $notice =  new Notice;
            $notice->create([
                    'user_id'=>auth()->guard('user')->user()->id,
                    'type'=>$this->type,
                    'is_active' => $this->is_active==true?0:1,
                    'start_date'=>$this->start_date,
                    'end_date'=>$this->end_date,
                    'title'=>$this->title,
                    'title'=>$this->title,
                    'description'=>$this->description,
                ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Notice Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Notice !!');
        }
    }

    public function edit(Notice $notice)
    {   
        $this->resetinput();
        $this->edit_id=$notice->id;
        $this->type=$notice->type;
        $this->start_date = date('Y-m-d', strtotime($notice->start_date));
        $this->end_date=date('Y-m-d', strtotime($notice->end_date));
        $this->title=$notice->title;
        $this->is_active=$notice->is_active==1?0:true;
        $this->description=$notice->description;
        $this->mode='edit';
    }

    public function update(Notice $notice)
    {
        $this->validate();
        
        DB::beginTransaction();

        try 
        {   
            $notice->update([
                'user_id'=>auth()->guard('user')->user()->id,
                'type'=>$this->type,
                'is_active' => $this->is_active==true?0:1,
                'start_date'=>$this->start_date,
                'end_date'=>$this->end_date,
                'title'=>$this->title,
                'title'=>$this->title,
                'description'=>$this->description,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Notice Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Notice !!');
        }
    }


    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }


    public function delete(Notice $notice)
    {  
        DB::beginTransaction();

        try
        {   
            $notice->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Notice Soft Deleted Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Notice !!');
        }
    }
    
    public function restore($id)
    {   
        DB::beginTransaction();

        try
        {   
            $notice = Notice::withTrashed()->find($id);
            $notice->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Notice Restored Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Notice !!');
        }
    }

    public function forcedelete()
    {   
        DB::beginTransaction();
        try 
        {
            $notice = Notice::withTrashed()->find($this->delete_id);
            $notice->forceDelete();
            $this->delete_id=null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Notice Deleted Successfully !!');
            
        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();
            
            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Notice !!');
            }
        }
    }

    public function changestatus(Notice $notice)
    {
        DB::beginTransaction();

        try 
        {   
            if($notice->is_active)
            {
                $notice->is_active=0;
            }
            else
            {
                $notice->is_active=1;
            }
            $notice->update();

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
        $notices=Notice::select('id','title','type','start_date','end_date','user_id','description','is_active','deleted_at')->with('user:name,id')->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.notice.all-notice',compact('notices'))->extends('layouts.user')->section('user');
    }
}
