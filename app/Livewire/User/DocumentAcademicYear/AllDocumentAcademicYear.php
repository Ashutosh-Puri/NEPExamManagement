<?php

namespace App\Livewire\User\DocumentAcademicYear;

use Excel;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Models\Documentacademicyear;
use App\Exports\User\DocumentAcademicYear\DocumentAcademicYearExport;

class AllDocumentAcademicYear extends Component
{   
    ### By Ashutosh
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'forcedelete'];

    public $perPage=10;
    public $search='';
    public $sortColumn="year_name";
    public $sortColumnBy="DESC";
    public $ext;

    public $year_name;
    public $start_date;
    public $end_date;
    public $active;

    #[Locked]
    public $mode='all';
    #[Locked]
    public $edit_id;
    #[Locked]
    public $delete_id;

    protected function rules()
    {
        return [
            'year_name' => ['required', 'string','max:40','unique:academicyears,year_name,' . ($this->mode == 'edit' ? $this->edit_id : ''),],
            'start_date' => ['nullable', 'date'],
            'end_date' => ['nullable', 'date'],
        ];
    }

    protected function messages()
    {
        return [
            'year_name.required' => 'Year name is required.',
            'year_name.string' => 'Year name must be a string.',
            'year_name.max' => 'Year name must not exceed 40 characters.',
            'year_name.unique' => 'Year name is already taken.',
            'start_date.date' => 'Start date must be a valid date.',
            'end_date.date' => 'End date must be a valid date.',
        ];
        return $messages;
    }

    public function updated($propertyName)
    {
        $this->validateOnly($propertyName);
    }

    protected function resetinput()
    {
        $this->reset([
            'year_name',
            'start_date',
            'end_date',
            'active',
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

            $filename="Document_Academic_Year_".now();
            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new DocumentAcademicYearExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new DocumentAcademicYearExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new DocumentAcademicYearExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Document Academic Year Exported Successfully !!');

            return $response;
        }
        catch (\Exception $e)
        {
            \Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Document Academic Year !!');
        }
    }

    public function add()
    {

        $this->validate();

        DB::beginTransaction();

        try
        {
            Documentacademicyear::query()->update(['active' => 0]);
            $academic_year =  new Documentacademicyear;
            $academic_year->year_name=$this->year_name;
            $academic_year->start_date=$this->start_date;
            $academic_year->end_date=$this->end_date;
            $academic_year->active = $this->active==true?0:1;
            $academic_year->save();

            DB::commit();

            $this->resetinput();

            $this->reset('mode');

            $this->dispatch('alert',type:'success',message:'Document Academic Year Created Successfully !!');

        } catch (\Exception $e)
        {

            DB::rollBack();

        $this->dispatch('alert',type:'error',message:'Failed to create Document Academic Year !!');
        }
    }

    public function edit(Documentacademicyear $academic_year)
    {
        $this->resetinput();
        $this->edit_id=$academic_year->id;
        $this->year_name= $academic_year->year_name;
        $this->start_date = date('Y-m-d', strtotime($academic_year->start_date));
        $this->end_date=date('Y-m-d', strtotime($academic_year->end_date));
        $this->active=$academic_year->active==1?0:true;
        $this->mode='edit';
    }

    public function update(Documentacademicyear $academic_year)
    {

        $this->validate();

        DB::beginTransaction();

        try
        {

            $academic_year->update([
                'year_name' => $this->year_name,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'active' => $this->active == true ? 0 : 1,
            ]);

            DB::commit();

            $this->resetinput();

            $this->mode='all';

            $this->dispatch('alert',type:'success',message:'Document Academic Year Updated Successfully !!');
        }
         catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Document Academic Year !!');
        }

    }

    public function update_status(Documentacademicyear $academic_year)
    {
        DB::beginTransaction();

        try
        {
            if($academic_year->active)
            {
                $academic_year->active=0;
            }
            else
            {
                $academic_year->active=1;
            }
            $academic_year->update();

            DB::commit();
            $this->dispatch('alert',type:'success',message:'Status Updated Successfully !!'  );
        }catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed to Update Status  !!');
        }
    }

    public function deleteconfirmation($academicyear_id)
    {
        $this->delete_id=$academicyear_id;
        $this->dispatch('delete-confirmation');
    }

    public function delete(Documentacademicyear  $academic_year)
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

    public function restore($academicyear_id)
    {

        DB::beginTransaction();

        try
        {
            $academicyear = Documentacademicyear::withTrashed()->findOrFail($academicyear_id);

            $academicyear->restore();

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
            $academic_year = Documentacademicyear::withTrashed()->find($this->delete_id);
            $academic_year->forceDelete();

            $this->delete_id=null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Document Academic Year Deleted Successfully !!');

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
                $this->dispatch('alert',type:'error',message:'Failed To Delete Document Academic Year !!');
            }
        }
    }


    public function render()
    {
        $academic_years=Documentacademicyear::select('id','year_name','start_date','end_date','active','deleted_at')->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.document-academic-year.all-document-academic-year',compact('academic_years'))->extends('layouts.user')->section('user');
    }
}
