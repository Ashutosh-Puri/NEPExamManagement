<?php

namespace App\Livewire\Faculty\FacultyHead;

use App\Models\Faculty;
use Livewire\Component;
use App\Models\Department;
use App\Models\Facultyhead;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Faculty\FacultyHead\FacultyHeadExport;

class AllFacultyHead extends Component
{
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'delete'];

    public $search='';
    public $perPage=10;
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;


    public $faculty_id;
    public $department_id;

    #[Locked]
    public $facultyhead_id;
    #[Locked]
    public $delete_id;
    #[Locked]
    public $mode='all';
    #[Locked]
    public $faculties;
    #[Locked]
    public $departments;

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
            'faculty_id' => ['required',Rule::exists(Faculty::class,'id')],
            'department_id' => ['required',Rule::exists(Department::class,'id')],
        ];
    }

    public function messages()
    {
        return [
            'faculty_id.required' => 'Please select a faculty.',
            'faculty_id.exists' => 'The selected faculty is invalid.',
            'department_id.required' => 'Please select a department.',
            'department_id.exists' => 'The selected department is invalid.',
        ];
    }

    public function resetinput()
    {
        $this->reset([
            'faculty_id',
            'department_id',
        ]);

    }

    #[Renderless]
    public function export()
    {
        try
        {
            $filename="Faculty_Head_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new FacultyHeadExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new FacultyHeadExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new FacultyHeadExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Faculty Head Data Exported Successfully !!');

            return $response;
        }
        catch (Exception $e)
        {
            Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Faculty Head Data !!');
        }
    }

    public function save()
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try
        {
            Facultyhead::create($validatedData);

            $this->dispatch('alert',type:'success',message:'Faculty Head Added Successfully');

            $this->resetinput();

            $this->setmode('all');

            DB::commit();

        } catch(\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Add Faculty Head. Please try again.');
        }
    }

    public function edit(Facultyhead $facultyhead)
    {
        if ($facultyhead){
            $this->facultyhead_id = $facultyhead->id;
            $this->faculty_id = $facultyhead->faculty_id;
            $this->department_id= $facultyhead->department_id;
        }else{
            $this->dispatch('alert',type:'error',message:'Faculty Head Details Not Found');
        }
        $this->setmode('edit');
    }

    public function update(Facultyhead $facultyhead)
    {
        $validatedData = $this->validate();

        DB::beginTransaction();

        try
        {
            $facultyhead->update($validatedData);

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Faculty Head Updated Successfully');
        }catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert', type: 'error', message: 'Failed To Update Faculty Head Please try again !!');
        }
    }

    public function delete()
    {
        DB::beginTransaction();

        try
        {
            $facultyhead = Facultyhead::withTrashed()->find($this->delete_id);

            $facultyhead->forceDelete();

            $this->delete_id = null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Faculty Head Deleted Successfully !!');

        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();

            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'info',message:'This record is associated with another data. You cannot delete it !!');
            }else
            {
                $this->dispatch('alert',type:'error',message:'Failed To Delete Faculty Head Data !!');
            }
        }
    }

    public function softdelete(Facultyhead $facultyhead)
    {
        DB::beginTransaction();

        try
        {
            $facultyhead->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Faculty Head Soft Deleted Successfully');

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Faculty Head !!');
        }
    }

    public function restore($faculty_head_id)
    {
        DB::beginTransaction();

        try
        {
            $facultyhead = Facultyhead::withTrashed()->findOrFail($faculty_head_id);

            $facultyhead->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Faculty Head Restored Successfully !!');

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Faculty Head Not Found !!');
        }
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function view(Facultyhead $facultyhead)
    {
        if ($facultyhead){
            $this->faculty_id= isset($facultyhead->faculty->faculty_name) ? $facultyhead->faculty->faculty_name : '';
            $this->department_id= isset($facultyhead->department->dept_name) ? $facultyhead->department->dept_name : '';
        }else{
            $this->dispatch('alert',type:'error',message:'Faculty Head Details Not Found');
        }
        $this->setmode('view');
    }

    public function changestatus(Facultyhead $facultyhead)
    {
        DB::beginTransaction();

        try {

            $facultyhead->status = $facultyhead->status == 0 ? 1 : 0;

            $facultyhead->update();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Faculty Head Status Updated Successfully !!');

        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Update Faculty Head Status !!');

        }
    }

    public function render()
    {
        $facultyheads=collect([]);

        if($this->mode !== 'all'){
            $this->departments = Department::where('status',1)->pluck('dept_name','id');
            $this->faculties= Faculty::where('active',1)->pluck('faculty_name','id');
        }
        $facultyheads = Facultyhead::with(['department', 'faculty'])->when($this->search, function($query, $search){
            $query->search($search);
        })->orderBy($this->sortColumn, $this->sortColumnBy)->withTrashed()->paginate($this->perPage);
        return view('livewire.faculty.faculty-head.all-faculty-head',compact('facultyheads'))->extends('layouts.faculty')->section('faculty');
    }
}
