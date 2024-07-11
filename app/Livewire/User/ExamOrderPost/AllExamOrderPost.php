<?php

namespace App\Livewire\User\ExamOrderPost;

use Excel;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Examorderpost;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\ExamOrderPost\ExportExamOrderPost;

class AllExamOrderPost extends Component
{   
    ## By Ashutosh
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'forcedelete'];   
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;
    public $post_name;
    public $status;
    
    #[Locked] 
    public $mode='all';
    #[Locked] 
    public $delete_id;
    #[Locked] 
    public $post_id;

    protected function rules()
    {
        return [
            'post_name' => ['required', 'string','max:50'],
        ];
    }

    public function messages()
    {   
        $messages = [
            'post_name.required' => 'The post name is required.',
            'post_name.string' => 'The post name must be a string.',
            'post_name.max' => 'The post name may not be greater than 50 characters.',         
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
            'post_name',
            'post_id',
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

            $filename="Exam_Order_Post_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportExamOrderPost($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportExamOrderPost($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportExamOrderPost($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Exam Order Post Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Exam Order Post !!');
        }

    }


    public function add()
    {   

        $this->validate();

        DB::beginTransaction();

        try 
        {   

            $examorderpost = new Examorderpost;
            $examorderpost->create([
                'post_name' => $this->post_name,
                'status'=>$this->status,          
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Exam Order Post Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Exam Order Post !!');
        }
    }


    public function edit(Examorderpost $examorderpost)
    {   
        $this->resetinput();
        $this->post_id=$examorderpost->id;
        $this->post_name= $examorderpost->post_name;
        $this->status= $examorderpost->status;
        $this->mode='edit';
    }

    public function update(Examorderpost $examorderpost)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {

            $examorderpost->update([
                'post_name' => $this->post_name,
                'status'=>$this->status,
               
            ]);

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Exam Order Post Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Exam Order Post !!');
        }
    }
   

    public function update_status(Examorderpost $examorderpost)
    {
        DB::beginTransaction();

        try 
        {   
            if($examorderpost->status)
            {
                $examorderpost->status=0;
            }
            else
            {
                $examorderpost->status=1;
            }
            $examorderpost->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }


    public function deleteconfirmation($post_id)
    {
        $this->delete_id=$post_id;
        $this->dispatch('delete-confirmation');
    }

    public function delete(Examorderpost  $examorderpost)
    {   
        
        DB::beginTransaction();

        try 
        {
            $examorderpost->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Order Post Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Exam Order Post !!');
        }
    }

    public function restore($post_id)
    {   
        DB::beginTransaction();

        try
        {
            $examorderpost = Examorderpost::withTrashed()->findOrFail($post_id);

            $examorderpost->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Order Post Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Exam Order Post !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        { 
            $examorderpost = Examorderpost::withTrashed()->find($this->delete_id);
            $examorderpost->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Order Post Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Exam Order Post !!');
            }
        }
    }
    
    public function render()
    {
        $Posts=Examorderpost::select('id','post_name','status','deleted_at')
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);
        
        return view('livewire.user.exam-order-post.all-exam-order-post',compact('Posts'))->extends('layouts.user')->section('user');
    }
}
