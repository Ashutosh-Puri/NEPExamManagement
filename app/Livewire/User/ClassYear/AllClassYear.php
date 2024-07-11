<?php

namespace App\Livewire\User\ClassYear;

use Excel;
use Livewire\Component;
use App\Models\Classyear;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\ClassYear\ClassYearExport;

class AllClassYear extends Component
{   
    # By Ashutosh
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    #[Locked] 
    public $delete_id;
    #[Locked] 
    public $mode='all';
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    public $classyear_name;
    public $class_degree_name;
    public $status;
    #[Locked] 
    public $edit_id;


    protected function rules()
    {
        return [
            'classyear_name' => ['required', 'string','max:255','unique:classyears,classyear_name,' . ($this->mode == 'edit' ? $this->edit_id : ''),],
            'class_degree_name' => ['required', 'string','max:100'],
        ];
    }

    public function messages()
    {   
        $messages = [
            'classyear_name.required' => 'The Class Year Name field is required.',
            'classyear_name.string' => 'The Class Year Name must be a string.',
            'classyear_name.max' => 'The  Class Year Name must not exceed :max characters.',
            'classyear_name.unique' => 'The Class Year Name has already been taken.',

            'class_degree_name.required' => 'The Class Degree Name field is required.',
            'class_degree_name.string' => 'The Class Degree Name must be a string.',
            'class_degree_name.max' => 'The  Class Degree Name must not exceed :max characters.',
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
            'edit_id',
            'classyear_name',
            'status',
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

            $filename="Class_Year_".now();

            $response = null;
            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ClassYearExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ClassYearExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ClassYearExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Class Year Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Class Year !!');
        }
    }

    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $class_year =  new Classyear;
            $class_year->create([
                'classyear_name' => $this->classyear_name,
                'class_degree_name' => $this->class_degree_name,
                'status' => $this->status==true?0:1,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Class Year Created Successfully !!');

        } catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create Class Year !!');
        }
    }


    public function edit(Classyear $class_year)
    {   
        $this->resetinput();
        $this->edit_id=$class_year->id;
        $this->classyear_name= $class_year->classyear_name;
        $this->class_degree_name= $class_year->class_degree_name;
        $this->status=$class_year->status==1?0:true;
        $this->mode='edit';
    }

    public function update(Classyear $class_year)
    {
        $this->validate();
        
        DB::beginTransaction();

        try 
        {   
            $class_year->update([
                'classyear_name' => $this->classyear_name,
                'class_degree_name' => $this->class_degree_name,
                'status' => $this->status == true ? 0 : 1,
            ]);

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Class Year Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Class Year !!');
        }
    }

    public function changestatus(Classyear $class_year)
    {
        DB::beginTransaction();

        try 
        {   
            if($class_year->status)
            {
                $class_year->status=0;
            }
            else
            {
                $class_year->status=1;
            }
            $class_year->update();

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


    public function delete(Classyear  $class_year)
    {  
        DB::beginTransaction();

        try
        {   
            $class_year->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Class Year Soft Deleted Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Class Year !!');
        }
    }
    
    public function restore($id)
    {   
        DB::beginTransaction();

        try
        {   
            $class_year = Classyear::withTrashed()->find($id);
            $class_year->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Class Year Restored Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Class Year !!');
        }
    }


    public function forcedelete()
    {   
        DB::beginTransaction();
        try 
        {
            $class_year = Classyear::withTrashed()->find($this->delete_id);
            $class_year->forceDelete();
            $this->delete_id=null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'Class Year Deleted Successfully !!');
            
        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();
            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } 
            else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Class Year !!');
            }
        }
    }

    public function render()
    {   
        $class_years=Classyear::select('id','classyear_name','class_degree_name','status','deleted_at')->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.class-year.all-class-year',compact('class_years'))->extends('layouts.user')->section('user');
    }
}
