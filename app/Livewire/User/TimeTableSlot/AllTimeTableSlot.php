<?php

namespace App\Livewire\User\TimeTableSlot;

use Excel;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Timetableslot;
use Livewire\Attributes\Locked;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use App\Exports\User\TimeTableSlot\TimeTableSlotExport;

class AllTimeTableSlot extends Component
{   
    # By Ashutosh
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'forcedelete'];
    #[Locked] 
    public $delete_id;

    public $mode='all';
    public $perPage=10;
    public $search='';
    public $sortColumn="timeslot";
    public $sortColumnBy="ASC";
    public $ext;

    public $slot;
    public $timeslot;
    public $start_time;
    public $end_time;
    public $isactive;
    #[Locked] 
    public $edit_id;


    protected function rules()
    {
        return [
            'timeslot' => ['required', 'string','max:80','unique:timetableslots,timeslot,' . ($this->mode == 'edit' ? $this->edit_id : ''),],
            'slot' => ['required', 'integer', 'digits_between:1,10'],
            'start_time' => ['required'],
            'end_time' => ['required'],
        ];
    }

    public function messages()
    {   
        $messages = [
            'start_time.required' => 'The Start Time field is required.',
            'start_time.time' => 'The Start Time must be an time.',
            'end_time.required' => 'The End Time field is required.',
            'end_time.time' => 'The End Time must be an time.',
            'timeslot.required' => 'The Time Slot field is required.',
            'timeslot.string' => 'The Time Slot must be a string.',
            'timeslot.max' => 'The  Time Slot must not exceed :max characters.',
            'timeslot.unique' => 'The Time Slot has already been taken.',
            'slot.required' => 'The Slot field is required.',
            'slot.integer' => 'The slot must be an integer.',
            'slot.digits_between' => 'The slot must be between :min and :max digits.',
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
            'slot',
            'timeslot',
            'isactive',
            'start_time',
            'end_time',
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

            $filename="Time_Table_Slot_".now();
            
            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response =  Excel::download(new TimeTableSlotExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response =  Excel::download(new TimeTableSlotExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response =  Excel::download(new TimeTableSlotExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Time Table Slot Exported Successfully !!');

            return $response;
        } 
        catch (\Exception $e) 
        {
            \Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Time Table Slot !!');
        }
    }

    public function add()
    {

        DB::beginTransaction();

        try
        {    
            $this->validate();

            $startTime =\DateTime::createFromFormat('H:i',  $this->start_time)->format('H:i:s.u');
            $endTime =\DateTime::createFromFormat('H:i',  $this->end_time)->format('H:i:s.u');
    
            $time_table_slot =  new Timetableslot;
            $time_table_slot->create([
                'slot' => $this->slot,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'timeslot' => $this->timeslot,
                'isactive' => $this->isactive==true?0:1,
            ]);
            $this->resetinput();
            $this->setmode('all');

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Time Table Slot Created Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Create Time Table Slot !!');
        }
    }


    public function edit(Timetableslot $time_table_slot)
    {   
        $this->resetinput();
        $this->edit_id=$time_table_slot->id;
        $this->slot= $time_table_slot->slot;
        $this->timeslot= $time_table_slot->timeslot;
        $this->isactive=$time_table_slot->isactive==1?0:true;
        if($time_table_slot->start_time)
        {   
            $this->start_time = \DateTime::createFromFormat('H:i:s',  $time_table_slot->start_time)->format('H:i');
        }

        if($time_table_slot->end_time)
        {
            $this->end_time = \DateTime::createFromFormat('H:i:s',  $time_table_slot->end_time)->format('H:i');
        }
  
        $this->setmode('edit');
    }

    public function update(Timetableslot $time_table_slot)
    {

        DB::beginTransaction();

        try
        {   
            $this->validate();

            $startTime =\DateTime::createFromFormat('H:i',  $this->start_time)->format('H:i:s.u');
            $endTime =\DateTime::createFromFormat('H:i',  $this->end_time)->format('H:i:s.u');

            $time_table_slot->update([
                'slot' => $this->slot,
                'start_time' => $startTime,
                'end_time' => $endTime,
                'timeslot' => $this->timeslot,
                'isactive' => $this->isactive == true ? 0 : 1,
            ]);
        
            $this->resetinput();
            $this->setmode('all');
            DB::commit();

            $this->dispatch('alert',type:'success',message:'Time Table Slot Updated Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Update Time Table Slot !!');
        }
        

    }

    public function status(Timetableslot $time_table_slot)
    {
        DB::beginTransaction();

        try 
        {   
            if($time_table_slot->isactive)
            {
                $time_table_slot->isactive=0;
            }
            else
            {
                $time_table_slot->isactive=1;
            }
            $time_table_slot->update();

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


    public function delete(Timetableslot  $time_table_slot)
    {  
        DB::beginTransaction();

        try
        {   
            $time_table_slot->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Time Table Slot Soft Deleted Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Time Table Slot  !!');
        }
    }

    public function restore($id)
    {   
        DB::beginTransaction();

        try
        {   
            $time_table_slot = Timetableslot::withTrashed()->find($id);
            $time_table_slot->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Time Table Slot Restored Successfully !!');
        } 
        catch (\Exception $e) 
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Restore Time Table Slot !!');
        }
    }

    
    public function forcedelete()
    {   
        DB::beginTransaction();
        try 
        {
            $time_table_slot = Timetableslot::withTrashed()->find($this->delete_id);
            $time_table_slot->forceDelete();

            $this->delete_id=null;
            DB::commit();

            $this->dispatch('alert',type:'success',message:'Time Table Slot Deleted Successfully !!');
            
        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();
            
            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'error',message:'This record is associated with another data. You cannot delete it !!');
            } else
            {   
                $this->dispatch('alert',type:'error',message:'Failed To Delete Time Table Slot !!');
            }
        }
    }


    public function render()
    {   
        if(isset($this->start_time) && $this->end_time)
        {
            $this->timeslot = "".\DateTime::createFromFormat('H:i',  $this->start_time)->format('h:i:s A')." To ".\DateTime::createFromFormat('H:i',  $this->end_time)->format('h:i:s A');
        }

        $time_table_slots =Timetableslot::select('id','slot','start_time','end_time','timeslot','isactive','deleted_at')->when($this->search, function ($query, $search) {
            $query->search($search);
        })->withTrashed()->orderBy($this->sortColumn, $this->sortColumnBy)->paginate($this->perPage);

        return view('livewire.user.time-table-slot.all-time-table-slot',compact('time_table_slots'))->extends('layouts.user')->section('user');
    }
}
