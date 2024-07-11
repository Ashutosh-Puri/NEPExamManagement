<?php

namespace App\Livewire\Faculty\SubjectVertical;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Subjectvertical;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Subjectbuckettypemaster;
use App\Exports\Faculty\SubjectVertical\SubjectVerticalExport;

class AllSubjectVertical extends Component
{
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'delete'];

    public $search='';
    public $perPage=10;
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    #[Locked]
    public $subjectvertical_id;
    #[Locked]
    public $delete_id;
    #[Locked]
    public $mode='all';
    #[Locked]
    public $subjectbucket_types;

    public $subject_vertical;
    public $subjectvertical_shortname;
    public $subjectbuckettype_id;
    public $is_active;


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
            'subject_vertical' => ['required', 'string', 'max:255',],
            'subjectvertical_shortname' => ['required', 'string', 'max:10',],
            'subjectbuckettype_id' => ['required', Rule::exists(Subjectbuckettypemaster::class,'id')],
        ];
    }

    public function messages()
    {
        return [
            'subject_vertical.required' => 'The subject vertical is required.',
            'subject_vertical.string' => 'The subject vertical must be a string.',
            'subject_vertical.max' => 'The subject vertical must not exceed 255 characters.',

            'subjectvertical_shortname.required' => 'The short name for the subject vertical is required.',
            'subjectvertical_shortname.string' => 'The short name must be a string.',
            'subjectvertical_shortname.max' => 'The short name must not exceed 10 characters.',

            'subjectbuckettype_id.required' => 'The subject bucket type is required.',
            'subjectbuckettype_id.string' => 'The subject bucket type must be a string.',
            'subjectbuckettype_id.max' => 'The subject bucket type must not exceed 50 characters.',
        ];
    }

    public function resetinput()
    {
        $this->reset([
            "subject_vertical",
            "subjectvertical_shortname",
            "subjectbuckettype_id",
        ]);
    }

    #[Renderless]
    public function export()
    {
        try
        {
            $filename="Subject_Verticals_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new SubjectVerticalExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new SubjectVerticalExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new SubjectVerticalExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Subject Verticals Data Exported Successfully !!');

            return $response;
        }
        catch (Exception $e)
        {
            Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Subject Verticals Data !!');
        }
    }

    public function save()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            $subjectvertical = Subjectvertical::create($validatedData);

            DB::commit();

            $this->resetinput();

            $this->setmode('all');

            $this->dispatch('alert', type: 'success', message: 'Subject Vertical Added Successfully !!');

        } catch (\Exception $e) {

            DB::rollback();

            $this->dispatch('alert', type: 'error', message: 'Failed to Add Subject Vertical Please try again !!');
        }
    }

    public function edit(Subjectvertical $subjectvertical)
    {
        if ($subjectvertical){
            $this->subjectvertical_id = $subjectvertical->id;
            $this->subject_vertical= $subjectvertical->subject_vertical;
            $this->subjectvertical_shortname= $subjectvertical->subjectvertical_shortname;
            $this->subjectbuckettype_id= $subjectvertical->subjectbuckettype_id;
        }else{
            $this->dispatch('alert',type:'error',message:'Subject Vertical Details Not Found');
        }
        $this->setmode('edit');
    }

    public function update(Subjectvertical $subjectvertical)
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try {
            $subjectvertical->update($validatedData);

            DB::commit();

            $this->setmode('all');

            $this->dispatch('alert', type: 'success', message: 'Subject Vertical Updated Successfully !!');

        } catch (\Exception $e) {

            DB::rollback();

            $this->dispatch('alert', type: 'error', message: 'Failed to Update Subject Vertical Please try again !!');
        }
    }

    public function delete()
    {
        DB::beginTransaction();

        try
        {
            $subjectvertical = Subjectvertical::withTrashed()->find($this->delete_id);

            $subjectvertical->forceDelete();

            $this->delete_id = null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Subject Vertical Deleted Successfully !!');

        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();

            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'info',message:'This record is associated with another data. You cannot delete it !!');
            }else
            {
                $this->dispatch('alert',type:'error',message:'Failed To Delete Subject Vertical Data !!');
            }
        }
    }

    public function softdelete(Subjectvertical $subjectvertical)
    {
        DB::beginTransaction();

        try
        {
            $subjectvertical->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Subject Vertical Soft Deleted Successfully !!');

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Subject Vertical !!');
        }
    }

    public function restore($subject_vertical_id)
    {
        DB::beginTransaction();

        try
        {
            $subjectvertical = Subjectvertical::withTrashed()->findOrFail($subject_vertical_id);

            $subjectvertical->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Subject Vertical Restored Successfully !!');

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Subject Vertical Not Found !!');
        }
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function view(Subjectvertical $subjectvertical)
    {
        if ($subjectvertical){
            $this->subject_vertical= $subjectvertical->subject_vertical;
            $this->subjectvertical_shortname= $subjectvertical->subjectvertical_shortname;
            $this->subjectbuckettype_id= isset($subjectvertical->buckettype->buckettype_name) ? $subjectvertical->buckettype->buckettype_name : '';
        }else{
            $this->dispatch('alert',type:'error',message:'Subject Vertical Details Not Found');
        }
        $this->setmode('view');
    }

    public function changestatus(Subjectvertical $subjectvertical)
    {
        DB::beginTransaction();

        try {

            $subjectvertical->is_active = $subjectvertical->is_active == 0 ? 1 : 0;

            $subjectvertical->update();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Subject Vertical Status Updated Successfully !!');

        } catch (\Exception $e) {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Update Subject Vertical Status !!');

        }
    }

    public function render()
    {
        $subjectverticals=collect([]);

        if($this->mode!=='all'){
            $this->subjectbucket_types = Subjectbuckettypemaster::pluck('buckettype_name','id');
        }

        $subjectverticals = Subjectvertical::with(['buckettype:id,buckettype_name'])->when($this->search, function($query, $search){
            $query->search($search);
        })->orderBy($this->sortColumn, $this->sortColumnBy)->withTrashed()->paginate($this->perPage);

        return view('livewire.faculty.subject-vertical.all-subject-vertical',compact('subjectverticals'))->extends('layouts.faculty')->section('faculty');
    }
}
