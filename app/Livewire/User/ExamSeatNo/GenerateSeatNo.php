<?php

namespace App\Livewire\User\ExamSeatNo;

use App\Models\Exam;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Locked;
use App\Models\Exampatternclass;
use App\Models\Examstudentseatno;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class GenerateSeatNo extends Component
{   
    ## By Ashutosh
    use WithPagination;
    protected $listeners = ['delete-confirmed'=>'forcedelete'];

    public $perPage=10;
    public $search='';
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;
    public $exam_ids;

    public function generate_class_seat_numbers(Exampatternclass $exampatternclass)
    {   

        DB::beginTransaction();

        try 
        {   
            $exam_seatno_data = Examstudentseatno::where('exam_patternclasses_id',$exampatternclass->id)->get();

            if(!empty( $exam_seatno_data))
            {

                $exampatternclasses=Exampatternclass::withCount('examstudentseatnos')->where('exam_id',$exampatternclass->exam_id)->get();


                
                if($exampatternclasses->pluck('examstudentseatnos_count')->sum()==0)
                { 
                    $seatno=1001;
                }
                else
                { 
                    $exampatternclasses_1=Exampatternclass::withMax('examstudentseatnos','seatno')->where('exam_id',$exampatternclass->exam_id)->get();

                    $a=$exampatternclasses_1->pluck('examstudentseatnos_max_seatno')->max()+40;
    
                    $b=$a%10;
                    $c=10-$b;
                    $seatno=$a+$c+1;
                }
                
                $examformmasters = $exampatternclass->patternclass->examformmasters->where('exam_id',$exampatternclass->exam_id)->where('inwardstatus', 1);

                $student_data=collect();
    
                foreach($examformmasters as $examformmaster)
                {
                    if($examformmaster->student->currentclassstudents->where('pfstatus','!=',-1)->last())     
                    {            
                        $pfstatus=$examformmaster->student->currentclassstudents->where('pfstatus','!=',-1)->last()->pfstatus;
                    }
                    else
                    {
                        $pfstatus=-1;
                    }
                    
                    $student_data->add([
                        'student_name'=> $examformmaster->student->student_name,
                        'prn'=> $examformmaster->student->prn,
                        'id'=> $examformmaster->student->id,
                        'exam_patternclasses_id'=>$exampatternclass->id,
                        'pfstatus'=>$pfstatus
                    ]);  
                            
                }

                $sorted = $student_data->sortBy([
                    ['pfstatus', 'desc'],
                    ['student_name', 'asc'],
                ]);
                        
                $sortdata=$sorted->values()->all();

                $values=collect();

                $college_id = isset(Auth::user()->college_id)?Auth::user()->college_id:NULL;
 
                foreach($sortdata as $examformmaster)
                {
                    $values->add([
                        'prn'=>$examformmaster['prn'],
                        'student_id'=>$examformmaster['id'],
                        'exam_patternclasses_id'=>$examformmaster['exam_patternclasses_id'],
                        'seatno'=>$seatno++,
                        'college_id'=> $college_id,
                        "created_at" => now(), 
                        "updated_at" =>now()
                    ]);
                    
                }

                $exampatternclass->examstudentseatnos()->insert($values->toArray());
            }

            DB::commit();

            $this->dispatch('alert', type: 'success', message: 'Seat Numbers Generated Successfully !!');   

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Generate Seat Numbers !!');
        }
    }
   
    public function regenerate_class_seat_numbers(Exampatternclass $exampatternclass)
    {   
        DB::beginTransaction();

        try 
        {   
            $exam_seatnos=Examstudentseatno::where('exam_patternclasses_id',$exampatternclass->id)->get();

            if(!empty($exam_seatnos))
            {   
                $seatno = $exam_seatnos->last()->seatno + 1;
    
                $examformmasters = $exampatternclass->patternclass->examformmasters->where('inwardstatus', 1)->where('exam_id',$exampatternclass->exam_id);
    
                $student_data=collect();
            
                foreach($examformmasters as $examformmaster)
                {
                    if(($examformmaster->student->examstudentseatnos->where('exam_patternclasses_id',$exampatternclass->id))->isEmpty())
                    {
                        $student_data->add([
                            'student_name'=> $examformmaster->student->student_name,
                            'prn'=> $examformmaster->student->prn,
                            'id'=> $examformmaster->student->id,
                            'exam_patternclasses_id'=>$exampatternclass->id,
                            'pfstatus'=>-1
                        ]);  
                    }
                }
                
                $sorted = $student_data->sortBy([
                    ['pfstatus', 'desc'],
                    ['student_name', 'asc'],
                ]);
                    
                 $sortdata=$sorted->values()->all();
                
                $values=collect();
                
                $college_id = isset(Auth::user()->college_id)?Auth::user()->college_id:NULL;
 
                foreach($sortdata as $examformmaster)
                {
                    $values->add([
                        'prn'=>$examformmaster['prn'],
                        'student_id'=>$examformmaster['id'],
                        'exam_patternclasses_id'=>$examformmaster['exam_patternclasses_id'],
                        'seatno'=>$seatno++,
                        'college_id'=> $college_id,
                        "created_at" => now(), 
                        "updated_at" => now()
                    ]);
                    
                }
                
                $exampatternclass->examstudentseatnos()->insert($values->toArray());
            }

            DB::commit();

            $this->dispatch('alert', type: 'success', message: 'Seat Numbers Regenerated Successfully !!');   

        } 
        catch (\Exception $e) 
        {   
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Regenerate Seat Numbers !!');
        }
       
    }

    public function delete_class_seat_numbers(Exampatternclass $exampatternclass)
    {   

        DB::beginTransaction();

        try 
        {  
            Examstudentseatno::where('exam_patternclasses_id', $exampatternclass->id)->where('printstatus', 0)->delete();

            DB::commit();

            $this->dispatch('alert', type: 'success', message: 'Seat Numbers Deleted Successfully !!');   

        } 
        catch (\Exception $e) 
        {   
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Delete Seat Numbers !!');
        }

    }

    public function generate_all_class_seat_numbers()
    {
        set_time_limit(600);
        ini_set('memory_limit', '1024M');

        DB::beginTransaction();

        try 
        {   

            $all_exampatternclasses = Exampatternclass::whereIn('exam_id',$this->exam_ids)->get();  

            foreach ($all_exampatternclasses as $exampatternclass) 
            {
                $exam_seatno_data = Examstudentseatno::where('exam_patternclasses_id',$exampatternclass->id)->get();
    
                if(!empty( $exam_seatno_data))
                {
    
                    $exampatternclasses=Exampatternclass::withCount('examstudentseatnos')->where('exam_id',$exampatternclass->exam_id)->get();
    
    
                    
                    if($exampatternclasses->pluck('examstudentseatnos_count')->sum()==0)
                    { 
                        $seatno=1001;
                    }
                    else
                    { 
                        $exampatternclasses_1=Exampatternclass::withMax('examstudentseatnos','seatno')->where('exam_id',$exampatternclass->exam_id)->get();
    
                        $a=$exampatternclasses_1->pluck('examstudentseatnos_max_seatno')->max()+40;
        
                        $b=$a%10;
                        $c=10-$b;
                        $seatno=$a+$c+1;
                    }
                    
                    $examformmasters = $exampatternclass->patternclass->examformmasters->where('exam_id',$exampatternclass->exam_id)->where('inwardstatus', 1);
    
                    $student_data=collect();
        
                    foreach($examformmasters as $examformmaster)
                    {
                        if($examformmaster->student->currentclassstudents->where('pfstatus','!=',-1)->last())     
                        {            
                            $pfstatus=$examformmaster->student->currentclassstudents->where('pfstatus','!=',-1)->last()->pfstatus;
                        }
                        else
                        {
                            $pfstatus=-1;
                        }
                        
                        $student_data->add([
                            'student_name'=> $examformmaster->student->student_name,
                            'prn'=> $examformmaster->student->prn,
                            'id'=> $examformmaster->student->id,
                            'exam_patternclasses_id'=>$exampatternclass->id,
                            'pfstatus'=>$pfstatus
                        ]);  
                                
                    }
    
                    $sorted = $student_data->sortBy([
                        ['pfstatus', 'desc'],
                        ['student_name', 'asc'],
                    ]);
                            
                    $sortdata=$sorted->values()->all();
    
                    $values=collect();
    
                    $college_id = isset(Auth::user()->college_id)?Auth::user()->college_id:NULL;
     
                    foreach($sortdata as $examformmaster)
                    {
                        $values->add([
                            'prn'=>$examformmaster['prn'],
                            'student_id'=>$examformmaster['id'],
                            'exam_patternclasses_id'=>$examformmaster['exam_patternclasses_id'],
                            'seatno'=>$seatno++,
                            'college_id'=> $college_id,
                            "created_at" => now(), 
                            "updated_at" =>now()
                        ]);
                        
                    }
    
                    $exampatternclass->examstudentseatnos()->insert($values->toArray());
                }
            }

            DB::commit();

            $this->dispatch('alert', type: 'success', message: 'All Class Seat Numbers Generated Successfully !!');   

        } 
        catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Generate All Class Seat Numbers !!');
        }
    }

    public function delete_all_class_seat_numbers()
    {  

        set_time_limit(600);
        ini_set('memory_limit', '1024M');

        DB::beginTransaction();

        try 
        {   
            
            $exampatternclass_ids= Exampatternclass::whereIn('exam_id',$this->exam_ids)->pluck('id');  

            Examstudentseatno::whereIn('exam_patternclasses_id', $exampatternclass_ids)->where('printstatus', 0)->delete();
            
            DB::commit();

            $this->dispatch('alert', type: 'success', message: 'All Class Seat Numbers Deleted Successfully !!');   

        }catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Delete All Class Seat Numbers !!');
        }

    }

    public function regenerate_all_class_seat_numbers()
    {
        set_time_limit(600);
        ini_set('memory_limit', '1024M');

        DB::beginTransaction();

        try 
        {   

            $all_exampatternclasses = Exampatternclass::whereIn('exam_id',$this->exam_ids)->get();  

            foreach ($all_exampatternclasses as $exampatternclass) 
            {
                $exam_seatnos=Examstudentseatno::where('exam_patternclasses_id',$exampatternclass->id)->get();

                if(!empty($exam_seatnos))
                {   
                    $seatno = $exam_seatnos->last()->seatno + 1;
        
                    $examformmasters = $exampatternclass->patternclass->examformmasters->where('inwardstatus', 1)->where('exam_id',$exampatternclass->exam_id);
        
                    $student_data=collect();
                
                    foreach($examformmasters as $examformmaster)
                    {
                        if(($examformmaster->student->examstudentseatnos->where('exam_patternclasses_id',$exampatternclass->id))->isEmpty())
                        {
                            $student_data->add([
                                'student_name'=> $examformmaster->student->student_name,
                                'prn'=> $examformmaster->student->prn,
                                'id'=> $examformmaster->student->id,
                                'exam_patternclasses_id'=>$exampatternclass->id,
                                'pfstatus'=>-1
                            ]);  
                        }
                    }
                    
                    $sorted = $student_data->sortBy([
                        ['pfstatus', 'desc'],
                        ['student_name', 'asc'],
                    ]);
                        
                    $sortdata=$sorted->values()->all();
                    
                    $values=collect();
                    
                    $college_id = isset(Auth::user()->college_id)?Auth::user()->college_id:NULL;
    
                    foreach($sortdata as $examformmaster)
                    {
                        $values->add([
                            'prn'=>$examformmaster['prn'],
                            'student_id'=>$examformmaster['id'],
                            'exam_patternclasses_id'=>$examformmaster['exam_patternclasses_id'],
                            'seatno'=>$seatno++,
                            'college_id'=> $college_id,
                            "created_at" => now(), 
                            "updated_at" => now()
                        ]);
                        
                    }
                    
                    $exampatternclass->examstudentseatnos()->insert($values->toArray());
                }
            }

            DB::commit();

            $this->dispatch('alert', type: 'success', message: 'All Class Seat Numbers Regenerated Successfully !!');   

        }catch (\Exception $e) 
        {

            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Regenerate All Class Seat Numbers !!');
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

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function mount()
    {
        $this->exam_ids = Exam::where('status',1)->pluck('id');
    }

    public function render()
    {
        $exampatternclasses=Exampatternclass::select('id','exam_id','patternclass_id','deleted_at')
        ->with(['patternclass.pattern:id,pattern_name','patternclass.courseclass.course:id,course_name', 'patternclass.courseclass.classyear:id,classyear_name', 'exam:exam_name,id'])
        ->whereIn('exam_id', $this->exam_ids)    
        ->when($this->search, function ($query, $search) {  $query->search($search); })
        ->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.exam-seat-no.generate-seat-no',compact('exampatternclasses'))->extends('layouts.user')->section('user');
    }
}
