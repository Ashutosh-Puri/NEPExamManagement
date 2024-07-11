<?php

namespace App\Livewire\Faculty\SubjectType;

use Livewire\Component;
use App\Models\Subjecttype;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Faculty\SubjectType\SubjectTypeExport;

class AllSubjectType extends Component
{
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'delete'];

    public $search='';
    public $perPage=10;
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    #[Locked]
    public $delete_id;
    #[Locked]
    public $subjecttype_id;
    #[Locked]
    public $mode='all';

    public $type_name;
    public $description;

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function setmode($mode)
    {
        if($mode=='add')
        {
            $this->resetinput();
        }
        if($mode=='edit')
        {
            $this->resetValidation();
        }
        $this->mode=$mode;
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

    protected function rules()
    {
        return [
            'type_name' => ['required', 'string', 'min:1','max:255',],
            'description' => ['required', 'string', 'min:2','max:100',],
        ];
    }

    public function messages()
    {
        return [
            'type_name.required' => 'The type name is required.',
            'type_name.string' => 'The type name must be a string.',
            'type_name.min' => 'The type name must be at least :min characters.',
            'type_name.max' => 'The type name must not exceed :max characters.',

            'description.required' => 'The type short name is required.',
            'description.string' => 'The type short name must be a string.',
            'description.min' => 'The type short name must be at least :min characters.',
            'description.max' => 'The type short name must not exceed :max characters.',
        ];
    }

    public function resetinput()
    {
        $this->reset([
            "type_name",
            "description",
        ]);
    }

    #[Renderless]
    public function export()
    {
        try
        {
            $filename="Subject_Types_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new SubjectTypeExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new SubjectTypeExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new SubjectTypeExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Subject Types Data Exported Successfully !!');

            return $response;
        }
        catch (Exception $e)
        {
            Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Subject Types Data !!');
        }
    }

    public function save()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            $subjecttype = Subjecttype::create($validatedData);

            DB::commit();

            $this->resetinput();

            $this->setmode('all');

            $this->dispatch('alert',type:'success',message:'Subject Type Added Successfully !!');

        } catch (\Exception $e) {

            DB::rollback();

            $this->dispatch('alert',type:'error',message:'Failed to Add Subject Type Please try again !!');
        }
    }

    public function edit(Subjecttype $subjecttype)
    {
        if ($subjecttype){
            $this->subjecttype_id = $subjecttype->id;
            $this->type_name= $subjecttype->type_name;
            $this->description= $subjecttype->description;
        }else{
            $this->dispatch('alert',type:'error',message:'Subject Type Details Not Found');
        }
        $this->setmode('edit');
    }

    public function update(Subjecttype $subjecttype)
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            $subjecttype->update($validatedData);

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Subject Type Updated Successfully !!');

            $this->setmode('all');

        } catch (\Exception $e) {

            DB::rollback();

            $this->dispatch('alert',type:'error',message:'Failed to Update Subject Type Please try again !!');
        }
    }

    public function delete()
    {
        DB::beginTransaction();

        try
        {
            $subjecttype = Subjecttype::withTrashed()->find($this->delete_id);

            $subjecttype->forceDelete();

            $this->delete_id = null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Subject Type Deleted Successfully !!');

        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();

            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'info',message:'This record is associated with another data. You cannot delete it !!');
            }else
            {
                $this->dispatch('alert',type:'error',message:'Failed To Delete Subject Type Data !!');
            }
        }
    }

    public function softdelete(Subjecttype $subjecttype)
    {
        DB::beginTransaction();

        try
        {
            $subjecttype->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Subject Type Soft Deleted Successfully !!');

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Subject Type !!');
        }
    }

    public function restore($subject_type_id)
    {
        DB::beginTransaction();

        try
        {
            $subjecttype = Subjecttype::withTrashed()->findOrFail($subject_type_id);

            $subjecttype->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Subject Type Restored Successfully !!');

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Subject Type Not Found !!');
        }
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function view(Subjecttype $subjecttype)
    {
        if ($subjecttype){
            $this->type_name= $subjecttype->type_name;
            $this->description= $subjecttype->description;
        }else{
            $this->dispatch('alert',type:'error',message:'Subject Type Details Not Found');
        }
        $this->setmode('view');
    }

    public function changestatus(Subjecttype $subjecttype)
    {
        DB::beginTransaction();

        try {

            $subjecttype->is_active = $subjecttype->is_active == 0 ? 1 : 0;

            $subjecttype->update();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Subject Type Status Updated Successfully !!');

        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Update Subject Type Status !!');

        }
    }

    public function render()
    {
        $subjecttypes = Subjecttype::when($this->search, function($query, $search){
            $query->search($search);
        })->orderBy($this->sortColumn, $this->sortColumnBy)->withTrashed()->paginate($this->perPage);
        return view('livewire.faculty.subject-type.all-subject-type',compact('subjecttypes'))->extends('layouts.faculty')->section('faculty');
    }
}
