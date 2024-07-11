<?php

namespace App\Livewire\User\AcademicYear;

use Excel;
use Livewire\Component;
use App\Models\Academicyear;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use App\Exports\User\AcademicYear\AcademicYearExport;

class AllAcademicYear extends Component
{   
    # By Ashutosh
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    #[Locked] 
    public $delete_id;
    #[Locked] 
    public $mode='all';
    #[Locked] 
    public $edit_id;
    public $perPage=10;
    public $search='';
    public $sortColumn="year_name";
    public $sortColumnBy="DESC";
    public $ext;
    public $year_name;
    public $active=null;


    protected function rules()
    {
        return [
            'year_name' => ['required', 'string','max:40','unique:academicyears,year_name,' . ($this->mode == 'edit' ? $this->edit_id : ''),],
        ];
    }

    public function messages()
    {   
        return [
            'year_name.required' => 'The Academic Year field is required.',
            'year_name.string' => 'The Academic Year must be a string.',
            'year_name.max' => 'The Academic Year must not exceed :max characters.',
            'year_name.unique' => 'The Academic Year has already been taken.',
        ];

    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    public function resetinput()
    {   
        $this->reset(
            [
                'year_name',
                'active'
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

            $filename="Academic_Year_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new AcademicYearExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new AcademicYearExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new AcademicYearExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Academic Year Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Academic Year !!');
        }
    }

    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            if($this->active==null)
            {
                Academicyear::query()->update(['active' => 0]);
            }

            Academicyear::create([
                'year_name'=>$this->year_name,
                'active'=>$this->active==true?0:1,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Academic Year Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Academic Year !!');
        }
    }

    public function edit(Academicyear $academic_year)
    {   
        $this->resetinput();
        $this->edit_id=$academic_year->id;
        $this->year_name= $academic_year->year_name;
        $this->active=$academic_year->active==1?0:true;
        $this->mode='edit';
    }

    public function update(Academicyear $academic_year)
    {
        $this->validate();
        
        DB::beginTransaction();

        try 
        {   
            if($this->active==null)
            {
                Academicyear::query()->update(['active' => 0]);
            }

            $academic_year->update([
                'year_name' => $this->year_name,
                'active' => $this->active == true ? 0 : 1,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Academic Year Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Academic Year !!');
        }
    }

    public function status(Academicyear $academic_year)
    {   
        DB::beginTransaction();

        try 
        {   
            if( $academic_year->active)
            {
                $academic_year->active=0;
            }
            else 
            {   
                Academicyear::query()->update(['active' => 0]);
                $academic_year->active=1;
            }
            $academic_year->update();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Academic Year Status Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Academic Year Status  !!');
        }
    }

    public function deleteconfirmation($academic_year_id)
    {
        $this->delete_id=$academic_year_id;
        $this->dispatch('delete-confirmation');
    }


    public function delete(Academicyear  $academic_year)
    {  
        DB::beginTransaction();

        try
        {   
            $academic_year->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Academic Year Soft Deleted Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Academic Year !!');
        }
    }
    
    public function restore($academic_year_id)
    {   
        DB::beginTransaction();

        try
        {   
            $academic_year = Academicyear::withTrashed()->find($academic_year_id);
            $academic_year->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Academic Year Restored Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Academic Year !!');
        }
    }

    public function forcedelete()
    {   
        DB::beginTransaction();

        try 
        {
            $academic_year = Academicyear::withTrashed()->find($this->delete_id);
            $academic_year->forceDelete();
            $this->delete_id=null;

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Academic Year Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Academic Year !!');
            }
        }
    }

    public function render()
    {   
        $academic_years=Academicyear::select('id','year_name','active','deleted_at')->when($this->search, function ($query, $search) {
                $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);
            
        return view('livewire.user.academic-year.all-academic-year',compact('academic_years'))->extends('layouts.user')->section('user');
    }
}
