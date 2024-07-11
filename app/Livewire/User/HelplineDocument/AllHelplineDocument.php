<?php

namespace App\Livewire\User\HelplineDocument;

use Excel;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Models\Studenthelplinequery;
use App\Models\Studenthelplinedocument;
use App\Exports\User\HelplineDocumnet\HelplineDocumentExport;

class AllHelplineDocument extends Component
{   # By Ashutosh
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    #[Locked] 
    public $delete_id;
    #[Locked] 
    public $mode='all';
    public $perPage=10;
    public $search='';
    public $sortColumn="student_helpline_query_id";
    public $sortColumnBy="ASC";
    public $ext;

    #[Locked] 
    public $student_helpline_queries;
    public $student_helpline_query_id;
    public $document_name;
    public $is_active;
    #[Locked] 
    public $edit_id;


    protected function rules()
    {
        return [
            'student_helpline_query_id' => ['required', 'integer', Rule::exists('student_helpline_queries', 'id')],
            'document_name' => ['required', 'string','max:255', Rule::unique('student_helpline_documents')
            ->ignore($this->mode=='edit'?$this->edit_id:'')->where(function ($query){
                return $query->where('student_helpline_query_id',$this->student_helpline_query_id);
            })],
        ];
    }

    public function messages()
    {   
        $messages = [
            'document_name.required' => 'The Document Name field is required.',
            'document_name.string' => 'The Document Name must be a string.',
            'document_name.max' => 'The Document Name must not exceed :max characters.',
            'document_name.unique' => 'The Document Name must be unique for the selected Query.',
            'student_helpline_query_id.required' => 'The Query is required.',
            'student_helpline_query_id.integer' => 'The Query must be a number.',
            'student_helpline_query_id.exists' => 'The selected Query does not exist.',
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
                'student_helpline_query_id',
                'document_name',
                'is_active',
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

            $filename="Helpline_Document_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new HelplineDocumentExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new HelplineDocumentExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new HelplineDocumentExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Helpline Document Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Helpline Document !!');
        }
    }


    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $helpline_query_document =  new Studenthelplinedocument;
            $helpline_query_document->create([
                'student_helpline_query_id' => $this->student_helpline_query_id ,
                'document_name' => $this->document_name ,
                'is_active' => $this->is_active==true?0:1,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Helpline Document Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Helpline Document !!');
        }
    }

    public function edit(Studenthelplinedocument $helpline_query_document)
    {   
        $this->resetinput();
        $this->edit_id=$helpline_query_document->id;
        $this->student_helpline_query_id = $helpline_query_document->student_helpline_query_id ;
        $this->document_name=$helpline_query_document->document_name;
        $this->is_active=$helpline_query_document->is_active==1 ? 0 : true ;
        $this->mode='edit';
    }

    public function update(Studenthelplinedocument $helpline_query_document)
    {
        $this->validate();
        
        DB::beginTransaction();

        try 
        {   
            $helpline_query_document->update([
                'student_helpline_query_id' => $this->student_helpline_query_id ,
                'document_name' => $this->document_name ,
                'is_active' => $this->is_active==true ? 0 : 1,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Helpline Document Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Helpline Document !!');
        }
    }

    public function status(Studenthelplinedocument $helpline_query_document)
    {
        DB::beginTransaction();

        try 
        {   
            if($helpline_query_document->is_active)
            {
                $helpline_query_document->is_active=0;
            }
            else
            {
                $helpline_query_document->is_active=1;
            }
            $helpline_query_document->update();

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


    public function delete(Studenthelplinedocument  $helpline_query_document)
    {  
        DB::beginTransaction();

        try
        {   
            $helpline_query_document->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Helpline Document Soft Deleted Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Helpline Document !!');
        }
    }

    public function restore($id)
    {   
        DB::beginTransaction();

        try
        {   
            $helpline_query_document = Studenthelplinedocument::withTrashed()->find($id);
            $helpline_query_document->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Helpline Document Restored Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Helpline Document !!');
        }
    }

    public function forcedelete()
    {   
        DB::beginTransaction();
        try 
        {
            $helpline_query_document = Studenthelplinedocument::withTrashed()->find($this->delete_id);
            $helpline_query_document->forceDelete();
            $this->delete_id=null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Helpline Document Deleted Successfully !!');
            
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Helpline Document !!');
            }
        }
    }

    public function render()
    {   
        if($this->mode!=='all')
        {
            $this->student_helpline_queries=Studenthelplinequery::where('is_active',1)->pluck('query_name','id');
        }

        $student_helpline_documents=Studenthelplinedocument::select('id','student_helpline_query_id','document_name','deleted_at','is_active')->with('studenthelplinequery:query_name,id')->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.helpline-document.all-helpline-document',compact('student_helpline_documents'))->extends('layouts.user')->section('user');
    }

}
