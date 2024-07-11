<?php

namespace App\Livewire\User\Cgpa;

use Excel;
use App\Models\Cgpa;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\Cgpa\ExportCgpa;

class AllCgpa extends Component
{   
    ## By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;    
    public $max_gp;    
    public $min_gp;    
    public $grade;    
    public $description;  
    public $steps=1;
    public $current_step=1;

    #[Locked]   
    public $cgpa_id;    
    #[Locked] 
    public $delete_id;
    #[Locked]
    public $mode='all';

    protected function rules()
    {
        return [
        'max_gp' => ['required','numeric','between:0.00,9999.99'],
        'min_gp' =>  ['required','numeric','between:0.00,9999.99'],
        'grade' => ['required','max:5'],
        'description' => ['nullable','max:50'],  
        ];
    }

    public function messages()
    {   
        $messages = [
            'max_gp.required' => 'The Maximum Grade Point field is required.',
            'min_gp.required' => 'The Minimum Grade Point field is required.',
            'grade.required' => 'The Grade field is required.',
            'grade.max' => 'The Grade must not exceed :max characters.',
            'grade.max' => 'The Grade must not exceed :max characters.',
            'description.max' => 'The Description must not exceed :max characters.',
        ];
        return $messages;
    }

    public function resetinput()
    {
        $this->reset([
            'max_gp',
            'min_gp',
            'grade',
            'description',
            'cgpa_id',
        ]);
    }

    public function updated($property)
    {
        $this->validateOnly($property);
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

    public function add()
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {   
            $cgpa = new Cgpa;
            $cgpa->max_gp = $this->max_gp;
            $cgpa->min_gp = $this->min_gp;
            $cgpa->grade = $this->grade;
            $cgpa->description = $this->description;
            $cgpa->save();

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'CGPA Created Successfully !!');

        } catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to create CGPA !!');
        }
    }

    public function edit(Cgpa $cgpa){

        if ($cgpa) {
            $this->resetinput();       
            $this->cgpa_id=$cgpa->id;
            $this->max_gp = $cgpa->max_gp;     
            $this->min_gp = $cgpa->min_gp;
            $this->grade = $cgpa->grade ;
            $this->description = $cgpa->description ;
            $this->mode='edit';
        }
    }

    
    public function update(Cgpa  $cgpa)
    {   
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $cgpa->max_gp= $this->max_gp;
            $cgpa->min_gp= $this->min_gp;
            $cgpa->grade= $this->grade;
            $cgpa->description= $this->description;
            $cgpa->update();

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'CGPA Entry Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update CGPA Entry !!');
        }
    }

    public function deleteconfirmation($cgpa_id)
    {
        $this->delete_id=$cgpa_id;
        $this->dispatch('delete-confirmation');
    }

    public function delete(Cgpa  $cgpa)
    {   
        DB::beginTransaction();

        try 
        {
            $cgpa->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'CGPA Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete CGPA !!');
        }
    }

    public function restore($cgpa_id)
    {   
       
        DB::beginTransaction();

        try
        {
            $cgpa_id = Cgpa::withTrashed()->findOrFail($cgpa_id);

            $cgpa_id->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'CGPA Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore CGPA !!');
        }
    }

    public function forcedelete()
    {  
        DB::beginTransaction();
        try
        {
            $cgpa = cgpa::withTrashed()->find($this->delete_id);
            $cgpa->forceDelete();
            $this->delete_id=null;
            DB::commit();
            $this->dispatch('alert',type:'success',message:'CGPA Deleted Successfully !!');
        } catch (\Illuminate\Database\QueryException $e) 
        {
            DB::rollBack();
            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete CGPA !!');
            }
        }
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

    #[Renderless]
    public function export()
    {   
        try 
        {
            set_time_limit(600);
            ini_set('memory_limit', '1024M');

            $filename="CGPA_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportCgpa($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportCgpa($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportCgpa($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            
            }

            $this->dispatch('alert',type:'success',message:'CGPA Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export CGPA !!');
        }
    }

    public function render()
    {
        $cgpas=Cgpa::select('id','max_gp','min_gp','grade','description','deleted_at')
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);
        return view('livewire.user.cgpa.all-cgpa',compact('cgpas'))->extends('layouts.user')->section('user');
    }
}
