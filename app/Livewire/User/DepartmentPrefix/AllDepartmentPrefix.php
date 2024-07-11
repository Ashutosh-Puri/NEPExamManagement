<?php

namespace App\Livewire\User\DepartmentPrefix;

use App\Models\Pattern;
use Livewire\Component;
use App\Models\Department;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use App\Models\Departmentprefix;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\User\DepartmentPrefix\DepartmentPrefixExport;

class AllDepartmentPrefix extends Component
{   
    ### By Ashutosh
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
    public $deptprefix_id;
    #[Locked]
    public $mode='all';
    #[Locked]
    public $departments;
    #[Locked]
    public $patterns;

    public $dept_id;
    public $pattern_id;
    public $prefix;
    public $postfix;


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

        $this->resetValidation();

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
            'dept_id' => ['required', Rule::exists(Department::class,'id')],
            'pattern_id' => ['required', Rule::exists(Pattern::class,'id')],
            'prefix' => ['required', 'string', 'min:1','max:3',],
            'postfix' => ['required', 'string', 'min:1','max:1',],
        ];
    }

    public function messages()
    {
        return [
            'dept_id.required' => 'The department ID is required.',
            'dept_id.exists' => 'The selected department ID is invalid.',
            'pattern_id.required' => 'The pattern ID is required.',
            'pattern_id.exists' => 'The selected pattern ID is invalid.',
            'prefix.required' => 'The prefix is required.',
            'prefix.string' => 'The prefix must be a string.',
            'prefix.min' => 'The prefix must be at least :min characters.',
            'prefix.max' => 'The prefix may not be greater than :max characters.',
            'postfix.required' => 'The postfix is required.',
            'postfix.string' => 'The postfix must be a string.',
            'postfix.min' => 'The postfix must be at least :min characters.',
            'postfix.max' => 'The postfix may not be greater than :max characters.',
        ];
    }

    protected function resetinput()
    {
        $this->reset([
            'dept_id',
            'pattern_id',
            'prefix',
            'postfix',
        ]);
    }

    #[Renderless]
    public function export()
    {
        try
        {
            $filename="Department_Prefix_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new DepartmentPrefixExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new DepartmentPrefixExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new DepartmentPrefixExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Department Prefix Exported Successfully !!');

            return $response;
        }
        catch (Exception $e)
        {
            Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Department Prefix !!');
        }

    }

    public function save()
    {
        $validatedData = $this->validate();
        $validatedData['prefix'] = strtoupper($validatedData['prefix']);
        $validatedData['postfix'] = strtoupper($validatedData['postfix']);

        DB::beginTransaction();

        try
        {
            Departmentprefix::create($validatedData);

            DB::commit();

            $this->resetinput();

            $this->setmode('all');

            $this->dispatch('alert',type:'success',message:'Department Prefix Added Successfully !!');

        } catch(\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Add Department Prefix. Please try again !!');
        }
    }

    public function edit(Departmentprefix $deptprefix)
    {
        if ($deptprefix){
            $this->deptprefix_id = $deptprefix->id;
            $this->dept_id = $deptprefix->dept_id;
            $this->pattern_id = $deptprefix->pattern_id;
            $this->prefix = $deptprefix->prefix;
            $this->postfix = $deptprefix->postfix;
        }else{
            $this->dispatch('alert',type:'error',message:'Department Prefix Details Not Found');
        }
        $this->setmode('edit');
    }

    public function update(Departmentprefix $deptprefix)
    {
        $validatedData = $this->validate();

        $validatedData['prefix'] = strtoupper($validatedData['prefix']);
        $validatedData['postfix'] = strtoupper($validatedData['postfix']);

        DB::beginTransaction();

        try
        {
            $deptprefix->update($validatedData);

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Department Prefix Updated Successfully !!');
        }catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Department Prefix !!');
        }
    }

    public function delete()
    {
        DB::beginTransaction();

        try
        {
            $deptprefix = Departmentprefix::withTrashed()->find($this->delete_id);

            $deptprefix->forceDelete();

            $this->delete_id = null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Department Prefix Deleted Successfully !!');

        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();

            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'info',message:'This record is associated with another data. You cannot delete it !!');
            }else
            {
                $this->dispatch('alert',type:'error',message:'Failed To Delete Department Prefix Data !!');
            }
        }
    }

    public function softdelete(Departmentprefix $department_prefix)
    {
        DB::beginTransaction();

        try
        {
            $department_prefix->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Department Prefix Entry Soft Deleted Successfully !!');

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Department Prefix !!');
        }
    }

    public function restore($department_prefix_id)
    {
        DB::beginTransaction();

        try
        {
            $admission_data = Departmentprefix::withTrashed()->findOrFail($department_prefix_id);

            $admission_data->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Department Prefix Restored Successfully !!');

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Department Prefix Not Found !!');
        }
    }

    public function deleteconfirmation($department_prefix_id)
    {
        $this->delete_id=$department_prefix_id;

        $this->dispatch('delete-confirmation');
    }

    public function view(Departmentprefix $deptprefix)
    {
        if ($deptprefix){
            $this->dept_id = isset($deptprefix->department->dept_name) ? $deptprefix->department->dept_name : '';
            $this->pattern_id = isset($deptprefix->pattern->pattern_name) ? $deptprefix->pattern->pattern_name : '';
            $this->prefix = $deptprefix->prefix;
            $this->postfix = $deptprefix->postfix;
        }else{
            $this->dispatch('alert',type:'error',message:'Department Prefix Details Not Found');
        }
        $this->setmode('view');
    }

    public function changestatus(Departmentprefix $deptprefix)
    {
        DB::beginTransaction();

        try 
        {   
            if($deptprefix->status)
            {
                $deptprefix->status=0;
            }
            else
            {
                $deptprefix->status=1;
            }
            $deptprefix->update();

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
        $deptprefixes = collect([]);

        if($this->mode == 'all' ){

            $this->patterns=Pattern::where('status',1)->pluck('pattern_name','id');
            $this->departments = Department::where('status',1)->pluck('dept_name','id');

            $deptprefixes = Departmentprefix::when($this->search, function($query, $search){
                $query->search($search);
            })->orderBy($this->sortColumn, $this->sortColumnBy)->withTrashed()->paginate($this->perPage);
        }
        return view('livewire.user.department-prefix.all-department-prefix',compact('deptprefixes'))->extends('layouts.user')->section('user');
    }
}
