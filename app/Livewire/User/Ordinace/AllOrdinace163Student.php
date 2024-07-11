<?php

namespace App\Livewire\User\Ordinace;

use Excel;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Studentordinace163;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\Ordinace\Ordinace163StudentExport;

class AllOrdinace163Student extends Component
{   
    # By Ashutosh
    use WithPagination;
    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

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

            $filename="Ordinace_163_Students_".now();

            $response = null;
            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new Ordinace163StudentExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new Ordinace163StudentExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new Ordinace163StudentExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Ordinace 163 Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            $this->dispatch('alert',type:'error',message:'Failed To Export Ordinace 163 !!');
        }
    }



    public function change_status(Studentordinace163 $ordinace163)
    {
        DB::beginTransaction();

        try 
        {   
            if($ordinace163->status)
            {
                $ordinace163->status=0;
            }
            else
            {
                $ordinace163->status=1;
            }
            $ordinace163->update();

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
        $studentordinace163s=Studentordinace163::with('ordinace163master:activity_name,id','student:student_name,id','exam:exam_name,id','patternclass.courseclass.course:course_name,id','patternclass.courseclass.classyear:classyear_name,id','patternclass.pattern:pattern_name,id','transaction:status,id,razorpay_payment_id')->when($this->search, function ($query, $search) {
            $query->search($search);
        })->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.ordinace.all-ordinace163-student',compact('studentordinace163s'))->extends('layouts.user')->section('user');
    }

}
