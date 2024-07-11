<?php

namespace App\Livewire\Faculty\SubjectCategory;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subjectcategory;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Faculty\SubjectCategory\SubjectCategoryExport;

class AllSubjectCategory extends Component
{
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'delete'];

    public $search='';
    public $perPage=10;
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    #[Locked]
    public $subjectcategory_id;
    #[Locked]
    public $delete_id;
    #[Locked]
    public $mode='all';

    public $subjectcategory;
    public $subjectcategory_shortname;

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
            'subjectcategory' => ['required', 'string', 'max:255',],
            'subjectcategory_shortname' => ['required', 'string', 'max:10',],
        ];
    }

    public function messages()
    {
        return [
            'subjectcategory.required' => 'The subject category is required.',
            'subjectcategory.string' => 'The subject category must be a string.',
            'subjectcategory.max' => 'The subject category must not exceed 255 characters.',

            'subjectcategory_shortname.required' => 'The short name for the subject category is required.',
            'subjectcategory_shortname.string' => 'The short name must be a string.',
            'subjectcategory_shortname.max' => 'The short name must not exceed 10 characters.',
        ];
    }

    public function resetinput()
    {
        $this->reset([
            "subjectcategory",
            "subjectcategory_shortname",
        ]);
    }

    #[Renderless]
    public function export()
    {
        try
        {
            $filename="Subject_Categories_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new SubjectCategoryExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new SubjectCategoryExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new SubjectCategoryExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Subject Category Data Exported Successfully !!');

            return $response;
        }
        catch (Exception $e)
        {
            Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Subject Category Data !!');
        }
    }

    public function save()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {

            $subjectcategory = Subjectcategory::create($validatedData);

            DB::commit();

            $this->resetinput();

            $this->setmode('all');

            $this->dispatch('alert', type: 'success', message: 'Subject Category Added Successfully !!');

        } catch (\Exception $e) {

            DB::rollback();

            $this->dispatch('alert', type: 'error', message: "Failed to Add Subject Category. Please try again !!");
        }
    }

    public function edit(Subjectcategory $subjectcategory)
    {
        if ($subjectcategory){
            $this->subjectcategory_id = $subjectcategory->id;
            $this->subjectcategory= $subjectcategory->subjectcategory;
            $this->subjectcategory_shortname= $subjectcategory->subjectcategory_shortname;
        }else{
            $this->dispatch('alert',type:'error',message:'Subject Category Details Not Found');
        }
        $this->setmode('edit');
    }

    public function update(Subjectcategory $subjectcategory)
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            $subjectcategory->update($validatedData);

            DB::commit();

            $this->setmode('all');

            $this->dispatch('alert', type: 'success', message: 'Subject Category Updated Successfully !!');

        } catch (\Exception $e) {

            DB::rollback();

            $this->dispatch('alert', type: 'error', message: 'Failed To Update Subject Category. Please try again !!');
        }
    }

    public function delete()
    {
        DB::beginTransaction();

        try
        {
            $subjectcategory = Subjectcategory::withTrashed()->find($this->delete_id);

            $subjectcategory->forceDelete();

            $this->delete_id = null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Subject Category Deleted Successfully !!');

        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();

            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'info',message:'This record is associated with another data. You cannot delete it !!');
            }else
            {
                $this->dispatch('alert',type:'error',message:'Failed To Delete Subject Category Data !!');
            }
        }
    }

    public function softdelete(Subjectcategory $subjectcategory)
    {
        DB::beginTransaction();

        try
        {
            $subjectcategory->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Subject Category Soft Deleted Successfully !!');

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Subject Category !!');
        }
    }

    public function restore($subject_category_id)
    {
        DB::beginTransaction();

        try
        {
            $subjectcategory = Subjectcategory::withTrashed()->findOrFail($subject_category_id);

            $subjectcategory->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Subject Category Restored Successfully !!');

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Subject Category Not Found !!');
        }
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function view(Subjectcategory $subjectcategory)
    {
        if ($subjectcategory){
            $this->subjectcategory= $subjectcategory->subjectcategory;
            $this->subjectcategory_shortname= $subjectcategory->subjectcategory_shortname;
        }else{
            $this->dispatch('alert',type:'error',message:'Subject Category Details Not Found');
        }
        $this->setmode('view');
    }

    public function changestatus(Subjectcategory $subjectcategory)
    {
        DB::beginTransaction();

        try {

            $subjectcategory->active = $subjectcategory->active == 0 ? 1 : 0;

            $subjectcategory->update();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Subject Category Status Updated Successfully !!');

        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Update Subject Category Status !!');

        }
    }

    public function render()
    {
        $subjectcategories=collect([]);

        if($this->mode==='all'){
            $subjectcategories = Subjectcategory::when($this->search, function($query, $search){
                $query->search($search);
            })->orderBy($this->sortColumn, $this->sortColumnBy)->withTrashed()->paginate($this->perPage);
        }
        return view('livewire.faculty.subject-category.all-subject-category',compact('subjectcategories'))->extends('layouts.faculty')->section('faculty');
    }
}
