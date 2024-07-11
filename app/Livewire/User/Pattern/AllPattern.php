<?php

namespace App\Livewire\User\Pattern;

use Excel;
use App\Models\College;
use App\Models\Pattern;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\Pattern\ExportPattern;


class AllPattern extends Component
{
    # By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    public $perPage=10; 
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;
    public $current_step=1;
    public $steps=1;
    public $pattern_name;
    public $pattern_startyear;
    public $pattern_valid;
    public $status;
    public $college_id ;

    #[Locked] 
    public $colleges;

    #[Locked] 
    public $mode='all';
    #[Locked] 
    public $delete_id;
    #[Locked] 
    public $pattern_id;
 
    protected function rules()
    {
        return [
        'pattern_name' => ['required','string','max:50'],
        'pattern_startyear' => ['required','string','max:4'],
        'pattern_valid' =>[ 'required','string','max:4'],
        'status' => ['required'],
        'college_id' => ['required',Rule::exists('colleges', 'id')],      
        ];
    }

    public function messages()
    {   
        $messages = [
            'pattern_name.required' => 'The pattern name is required.',
            'pattern_name.string' => 'The pattern name must be a string.',
            'pattern_name.max' => 'The pattern name may not be greater than 50 characters.',
            'pattern_startyear.required' => 'The pattern start year is required.',
            'pattern_startyear.string' => 'The pattern start year must be a string.',
            'pattern_startyear.max' => 'The pattern start year may not be greater than 4 characters.',
            'pattern_valid.required' => 'The pattern validity is required.',
            'pattern_valid.string' => 'The pattern validity must be a string.',
            'pattern_valid.max' => 'The pattern validity may not be greater than 4 characters.',
            'status.required' => 'The status is required.',
            'college_id.required' => 'The college ID is required.',
            'college_id.exists' => 'The selected college ID is invalid.',
        ];
        return $messages;
    }

    protected function resetinput()
    {
        $this->reset([
            'pattern_name',
            'pattern_startyear',
            'pattern_valid',
            'status',
            'college_id',
        ]);
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
        $this->mode=$mode;
        $this->resetValidation();
    }

    public function add()
    {
        DB::beginTransaction();

        try 
        {

            $validatedData = $this->validate();

            if ($validatedData) 
            {
                $pattern = new Pattern;
                $pattern->pattern_name = $this->pattern_name;
                $pattern->pattern_startyear = $this->pattern_startyear;
                $pattern->pattern_valid = $this->pattern_valid;
                $pattern->college_id = $this->college_id;
                $pattern->status = $this->status;
                $pattern->save();
            
                DB::commit();
                $this->resetinput();
                $this->reset('mode');
                $this->dispatch('alert',type:'success',message:'Pattern Added Successfully !!'  );
            }

        } catch (\Exception $e) 
        {
            DB::rollback();
            $this->dispatch('alert',type:'error',message:'Failed to create Pattern !!'  );
        }
       
    }
    
    public function update_status(Pattern $pattern)
    {
        DB::beginTransaction();

        try 
        {   
            if($pattern->status)
            {
                $pattern->status=0;
            }
            else
            {
                $pattern->status=1;
            }
            $pattern->update();

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
    
    public function delete(Pattern  $pattern)
    {   
        DB::beginTransaction();

        try 
        {
            $pattern->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Pattern Soft Deleted Successfully !!');

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Pattern !!');
        }
    }

    public function restore($pattern_id)
    {   
       

        DB::beginTransaction();

        try
        {
            $pattern = Pattern::withTrashed()->findOrFail($pattern_id);

            $pattern->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Pattern Restored Successfully !!');

        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Pattern !!');
        }
    }

    public function forcedelete()
    {   

        DB::beginTransaction();

        try 
        { 
            $pattern = Pattern::withTrashed()->find($this->delete_id);
            $pattern->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Pattern Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Pattern !!');
            }
        }
    }

    public function edit(Pattern $pattern)
    {       
        if($pattern)
        { 
            $this->resetinput();
            $this->pattern_id=$pattern->id;
            $this->pattern_name=$pattern->pattern_name;
            $this->pattern_startyear=$pattern->pattern_startyear;
            $this->pattern_valid=$pattern->pattern_valid;
            $this->status=$pattern->status;
            $this->college_id=$pattern->college_id;
            $this->mode='edit';
        }
    }

    public function update(Pattern  $pattern){  
            
        $this->validate();

        DB::beginTransaction();

        try 
        {
            $pattern->update([
                'pattern_name' => $this->pattern_name,
                'pattern_startyear' => $this->pattern_startyear,
                'pattern_valid' => $this->pattern_valid,
                'college_id' => $this->college_id,
                'status' => $this->status,                  
            ]);

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Pattern Updated Successfully !!'  );
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Pattern !!');
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

            $filename="Pattern_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExportPattern($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExportPattern($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExportPattern($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Pattern Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            \Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Pattern !!');
        }
       
    }


    public function render()
    {   
        if($this->mode!=='all')
        {   
            $this->colleges=College::where('status',1)->pluck('college_name','id');
        }

        $patterns=Pattern::with('college:id,college_name')->select('id','pattern_name','pattern_startyear','pattern_valid','college_id','status','deleted_at')
        ->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.pattern.all-pattern',compact('patterns'))->extends('layouts.user')->section('user');
    }
}
