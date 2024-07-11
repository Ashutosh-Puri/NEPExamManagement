<?php

namespace App\Livewire\User\InternalAudit\InternalTool;

use Exception;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use App\Models\Coursetypemaster;
use App\Models\Internaltoolview;
use App\Models\Internaltoolmaster;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Livewire\Attributes\Renderless;
use App\Models\Internaltooldocument;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Internaltooldocumentmaster;
use App\Exports\User\InternalAudit\InternalTool\InternalToolExport;

class AllInternalTool extends Component
{   
    ### By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'delete'];

    public $toolname;
    public $course_type;
    public $status;
    public $course_types;

    #[Locked]
    public $delete_id;
    #[Locked]
    public $internaltool_id;

    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    #[Locked]
    public $mode='all';
    public $ext;

    protected function rules()
    {
        $rules = [
            'toolname' => ['required', 'string', 'min:2', 'max:255'],
            'course_type' => ['required', Rule::exists(Coursetypemaster::class, 'course_type')],
        ];

        return $rules;
    }

    public function messages()
    {
        return [
            'toolname.required' => 'The tool name field is required.',
            'toolname.string' => 'The tool name must be a string.',
            'toolname.min' => 'The tool name must be at least :min characters.',
            'toolname.max' => 'The tool name may not be greater than :max characters.',
            'course_type.required' => 'The course type field is required.',
            'course_type.exists' => 'The selected course type is invalid.',
        ];
    }

    public function resetinput()
    {
        $this->reset([
            'toolname',
            'course_type',
            'internaltool_id'
        ]);
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
        $this->mode=$mode;
        $this->resetValidation();
    }

    public function save()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try
        {
            $internal_tool = Internaltoolmaster::create($validatedData);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Internal Tool Added Successfully');

        } catch (\Exception $e)
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Add Internal Tool. Please try again.');
        }
    }


    public function edit(Internaltoolmaster $internal_tool)
    {
        if ($internal_tool){
            $this->internaltool_id = $internal_tool->id;
            $this->toolname= $internal_tool->toolname;
            $this->course_type= $internal_tool->course_type;
        }else{
            $this->dispatch('alert',type:'error',message:'Internal tool Details Not Found');
        }
        $this->mode='edit';
    }

    public function update(Internaltoolmaster $internal_tool)
    {
        $validatedData = $this->validate();

        DB::beginTransaction();
        try
        {
            $internal_tool->update($validatedData);

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Internal tool Updated Successfully');
        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Error To Update Internal tool');
        }
    }


    public function delete()
    {   
        DB::beginTransaction();
        try
        {
            $internal_tool = Internaltoolmaster::withTrashed()->find($this->delete_id);
            $internal_tool->forceDelete();
            $this->delete_id = null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Internal Tool Deleted Successfully !!');
        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();
            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            }else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Internal Tool !!');
            }
        }
    }

    public function softdelete($id)
    {
        DB::beginTransaction();

        try
        {
            $internal_tool = Internaltoolmaster::withTrashed()->find($id);
            $internal_tool->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Internal Tool Soft Deleted Successfully');
        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Internal Tool Not Found !');
        }
    }

    public function restore($id)
    {
        DB::beginTransaction();

        try
        {
            $internal_tool = Internaltoolmaster::withTrashed()->find($id);
            $internal_tool->restore();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Internal Tool Restored Successfully');
        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Internal Tool Not Found');
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

            $filename="Internal_Tool_".now();
            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new InternalToolExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new InternalToolExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new InternalToolExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Internal Tool Exported Successfully !!');

            return $response;
        }
        catch (\Exception $e)
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Internal Tool !!');
        }
    }

    public function changestatus(Internaltoolmaster $internal_tool)
    {
        DB::beginTransaction();

        try 
        {

            $internal_tool->status = $internal_tool->status == 0 ? 1 : 0;

            $internal_tool->update();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Internal Tool Status Updated Successfully !!');

        } catch (\Exception $e) {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Update Internal Tool Status !!');

        }
    }

    public function view(Internaltoolmaster $internal_tool)
    {
        if ($internal_tool)
        {

            $this->toolname= $internal_tool->toolname;
            $this->course_type= $internal_tool->course_type;
        }
        else
        {
            $this->dispatch('alert',type:'error',message:'Internal Tool Details Not Found');
        }
        $this->mode='view';

    }


    public function render()
    {
        if($this->mode !== 'all')
        {
            $this->course_types = Coursetypemaster::pluck('course_type','id');
            $this->internaltool_documents = Internaltooldocumentmaster::pluck('doc_name','id');
        }

        $internal_tools = Internaltoolmaster::when($this->search, function($query, $search){
            $query->search($search);
        })->orderBy($this->sortColumn, $this->sortColumnBy)->withTrashed()->paginate($this->perPage);
        return view('livewire.user.internal-audit.internal-tool.all-internal-tool',compact('internal_tools'))->extends('layouts.user')->section('user');
    }
}
