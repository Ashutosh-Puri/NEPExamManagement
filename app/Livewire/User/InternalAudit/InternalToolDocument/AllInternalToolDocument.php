<?php

namespace App\Livewire\User\InternalAudit\InternalToolDocument;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use App\Models\Internaltoolmaster;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Models\Internaltooldocument;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Internaltooldocumentmaster;
use App\Exports\User\InternalAudit\InternalToolDocument\InternalToolDocumentExport;

class AllInternalToolDocument extends Component
{   
    ### By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'delete'];

    public $internaltooldoc_id;

    #[Locked]
    public $internaltooldocuments;
    public $internaltoolmaster_id;

    #[Locked]
    public $internaltoolmasters;
    public $is_multiple;
    public $status;

    #[Locked]
    public $delete_id;
    #[Locked]
    public $internaltooldocument_id;

    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";

    #[Locked]
    public $mode='all';
    public $ext;


    protected function rules()
    {
        return [
            'internaltoolmaster_id' => ['required', Rule::exists(Internaltoolmaster::class,'id') ],
            'internaltooldoc_id' => ['required', Rule::exists(Internaltooldocumentmaster::class,'id') ],
            'is_multiple' => ['nullable', 'boolean'],
        ];
    }


    public function messages()
    {
        return [
            'internaltoolmaster_id.required' => 'The internal tool master ID is required.',
            'internaltoolmaster_id.exists' => 'The selected internal tool master does not exist.',
            'internaltooldoc_id.required' => 'The internal tool document ID is required.',
            'internaltooldoc_id.exists' => 'The selected internal tool document does not exist.',
            'is_multiple.boolean' => 'The is multiple field is required.',
        ];
    }

    public function resetinput()
    {
        $this->reset(
            [
                'internaltooldoc_id',
                'internaltoolmaster_id',
                'is_multiple'
            ]
        );
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
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

    public function setmode($mode)
    {
        if($mode=='add')
        {
            $this->resetinput();
        }

        $this->resetValidation();
        $this->mode=$mode;
    }

    public function save()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try
        {
            $validatedData['is_multiple'] = $validatedData['is_multiple'] ? 1 : 0;
            $internaltool_document = Internaltooldocument::create($validatedData);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Internal Tool Document Added Successfully');

        } catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Add Internal Tool Document. Please try again.');
        }
    }

    public function edit(Internaltooldocument $internaltool_document)
    {
        if ($internaltool_document){
            $this->internaltooldocument_id = $internaltool_document->id;
            $this->internaltoolmaster_id = $internaltool_document->internaltoolmaster_id;
            $this->internaltooldoc_id= $internaltool_document->internaltooldoc_id;
            $this->is_multiple = $internaltool_document->is_multiple == 1 ? true : false;
        }else{
            $this->dispatch('alert',type:'error',message:'Internal Tool Document Details Not Found');
        }
        $this->mode='edit';
    }

    public function update(Internaltooldocument $internaltool_document)
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

            $this->dispatch('alert',type:'success',message:'Internal Tool Document Updated Successfully');
        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Error To Update Internal Tool Document');
        }
    }


    public function delete()
    {   
        
        DB::beginTransaction();

        try
        {
            $internaltool_document = Internaltooldocument::withTrashed()->find($this->delete_id);
            $internaltool_document->forceDelete();
            $this->delete_id=null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:' Deleted Successfully !!');

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            }
            else
            {
                $this->dispatch('alert',type:'error',message:'Failed To Delete Internal Tool Document !!');
            }
        }
    }


    public function softdelete($id)
    {
        DB::beginTransaction();

        try
        {
            $internaltool_document = Internaltooldocument::withTrashed()->find($id);
            $internaltool_document->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Internal Tool Document Soft Deleted Successfully');
        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Internal Tool Document Not Found !');
        }
    }


    public function restore($id)
    {
        DB::beginTransaction();

        try
        {
            $internaltool_document = Internaltooldocument::withTrashed()->find($id);
            $internaltool_document->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Internal Tool Document Restored Successfully');
        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Internal Tool Document Not Found');
        }
    }


    public function updatedSearch()
    {
        $this->resetPage();
    }


    #[Renderless]
    public function export()
    {
        try
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="Internal_Tool_Documents_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new InternalToolDocumentExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new InternalToolDocumentExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new InternalToolDocumentExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Internal Tool Document Exported Successfully !!');

            return $response;
        }
        catch (\Exception $e)
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Internal Tool Document !!');
        }
    }

    public function changestatus(Internaltooldocument $internaltool_document)
    {
        DB::beginTransaction();

        try {

            $internaltool_document->status = $internaltool_document->status == 0 ? 1 : 0;

            $internaltool_document->update();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Internal Tool Document Status Updated Successfully !!');

        } catch (\Exception $e) {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Update Internal Tool Document Status !!');

        }
    }

    public function view(Internaltooldocument $internaltool_document)
    {
        if ($internaltool_document){
            $this->internaltooldoc_id= isset($internaltool_document->internaltooldocumentmaster->doc_name) ? $internaltool_document->internaltooldocumentmaster->doc_name : '';
            $this->internaltoolmaster_id= isset($internaltool_document->internaltoolmaster->toolname) ? $internaltool_document->internaltoolmaster->toolname : '';
            $this->is_multiple = $internaltool_document->is_multiple == 1 ? 'Yes' : 'No';
        }else{
            $this->dispatch('alert',type:'error',message:'Internal Tool Details Not Found');
        }
        $this->setmode('view');
    }

    public function render()
    {
        if($this->mode !== 'all'){
            $this->internaltooldocuments = Internaltooldocumentmaster::pluck('doc_name','id');
            $this->internaltoolmasters = Internaltoolmaster::pluck('toolname','id');
        }

        $internaltool_documents = Internaltooldocument::with(['internaltoolmaster:id,toolname','internaltooldocumentmaster:id,doc_name',])->when($this->search, function($query, $search){
            $query->search($search);
        })->orderBy($this->sortColumn, $this->sortColumnBy)->withTrashed()->paginate($this->perPage);
        return view('livewire.user.internal-audit.internal-tool-document.all-internal-tool-document',compact('internaltool_documents'))->extends('layouts.user')->section('user');
    }
}
