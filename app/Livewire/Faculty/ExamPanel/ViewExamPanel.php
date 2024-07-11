<?php

namespace App\Livewire\Faculty\ExamPanel;

use App\Models\Faculty;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Classview;
use App\Models\Exampanel;
use App\Models\Department;
use App\Models\Patternclass;
use Livewire\WithPagination;
use App\Models\Examorderpost;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Locked;
use App\Models\Hodappointsubject;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Renderless;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\Faculty\ExamPanel\ExamPanelExport;

class ViewExamPanel extends Component
{
    use WithPagination;

    protected $listeners = ['delete-confirmed'=>'delete'];

    public $search='';
    public $perPage=10;
    public $sortColumn="id";
    public $sortColumnBy="ASC";
    public $ext;

    #[Locked]
    public $exampanel_id;
    #[Locked]
    public $delete_id;
    #[Locked]
    public $mode='add';
    #[Locked]
    public $faculties;
    #[Locked]
    public $subjects;
    #[Locked]
    public $departments;
    #[Locked]
    public $posts;
    #[Locked]
    public $pattern_classes;

    public $post_id;
    public $selected_faculties=[];
    public $faculty_id;
    public $patternclass_id;
    public $subject_id;
    public $department_id;

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
            'patternclass_id' => ['required', 'integer', Rule::exists(Patternclass::class,'id'),],
            'subject_id' => ['required', Rule::exists(Subject::class,'id')],
            'department_id' => ['required', Rule::exists(Department::class,'id')],
            'post_id' => ['required', Rule::exists(Examorderpost::class,'id')],
            'selected_faculties' => ['required', ],
            // 'selected_faculties' => ['required', 'max:50'],
        ];
    }

    public function messages()
    {
        return [
            'patternclass_id.required' => 'The Class field is required.',
            'patternclass_id.integer' => 'The Class must be an integer.',
            'patternclass_id.exists' => 'The selected Class is invalid.',

            'subject_id.required' => 'The Subject field is required.',
            'subject_id.exists' => 'The selected Subject is invalid.',
            'subject_id.integer' => 'The Subject must be an integer.',

            'department_id.required' => 'The Department field is required.',
            'department_id.integer' => 'The Department must be an integer.',
            'department_id.exists' => 'The selected Department is invalid.',

            'post_id.required' => 'The Post field is required.',
            'post_id.integer' => 'The Post must be an integer.',
            'post_id.exists' => 'The selected Post is invalid.',

            'selected_faculties.required' => 'Please select at least one faculty.',
        ];
    }

    public function resetinput()
    {
        $this->reset([
            'patternclass_id',
            'subject_id',
            'department_id',
            'post_id',
            'selected_faculties',
        ]);
    }

    #[Renderless]
    public function export()
    {
        try
        {
            $filename="Exam_Panel_".now();

            $response = null;

            switch ($this->ext) {
                case 'xlsx':
                    $response = Excel::download(new ExamPanelExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.xlsx');
                break;
                case 'csv':
                    $response = Excel::download(new ExamPanelExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.csv');
                break;
                case 'pdf':
                    $response = Excel::download(new ExamPanelExport($this->search, $this->sortColumn, $this->sortColumnBy), $filename.'.pdf', \Maatwebsite\Excel\Excel::DOMPDF,);
                break;
            }

            $this->dispatch('alert',type:'success',message:'Exam Panel Data Exported Successfully !!');

            return $response;
        }
        catch (Exception $e)
        {
            Log::error($e);

            $this->dispatch('alert',type:'error',message:'Failed To Export Exam Data !!');
        }
    }

    public function save()
    {
        DB::beginTransaction();

        try {
            $validatedData = $this->validate();
            $exampanelData = [];

            foreach ($this->selected_faculties as $facultyid) {
                $exampanelData[] = [
                    'faculty_id' => $facultyid,
                    'examorderpost_id' => $validatedData['post_id'],
                    'subject_id' => $validatedData['subject_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Exampanel::insert($exampanelData);

            DB::commit();

            $this->dispatch('alert', type: 'success', message: 'Exam Panels Added Successfully !!');

            $this->resetinput();
        } catch (\Exception $e) {

            DB::rollback();

            $this->dispatch('alert', type: 'error', message: 'Failed to Add Exam Panel. Please try again !!');
        }
    }

    public function edit(Exampanel $exampanel)
    {
        if ($exampanel) {

            $selectedFaculties = Exampanel::where('subject_id', $exampanel->subject_id)->get();

            $this->selected_faculties = $selectedFaculties->pluck('faculty_id')->toArray();

            $this->patternclass_id = $exampanel->subject->patternclass_id;
            $this->subject_id = $exampanel->subject_id;
            $this->post_id = $exampanel->examorderpost_id;
            $this->department_id = $exampanel->faculty->department_id;

            $allFaculties = Faculty::where('department_id', $this->department_id)->where('active', 1)->get();
            $this->faculties = $allFaculties->pluck('id')->toArray();

            $this->setmode('edit');
        } else {
            $this->dispatch('alert', type: 'error', message: 'Exam Panel not found !!');
        }
    }

    public function update()
    {
        DB::beginTransaction();

        try {
            $validatedData = $this->validate();
            $exampanelData = [];

            foreach ($this->selected_faculties as $facultyid) {
                $exampanelData[] = [
                    'faculty_id' => $facultyid,
                    'examorderpost_id' => $validatedData['post_id'],
                    'subject_id' => $validatedData['subject_id'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }

            Exampanel::where('examorderpost_id', $validatedData['post_id'])->delete();

            Exampanel::insert($exampanelData);

            DB::commit();

            $this->dispatch('alert', type: 'success', message: 'Exam Panels Updated Successfully !!');

            $this->resetinput();

            $this->setmode('add');

        } catch (\Exception $e) {

            DB::rollback();

            $this->dispatch('alert', type: 'error', message: 'Failed to update Exam Panel Please try again !!');
        }
    }

    public function delete()
    {
        DB::beginTransaction();

        try
        {
            $exampanel_faculty = Exampanel::withTrashed()->find($this->delete_id);

            $exampanel_faculty->forceDelete();

            $this->delete_id = null;

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Faculty Deleted From Exam Panel Successfully !!');

        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();

            if ($e->errorInfo[1] == 1451) {

                $this->dispatch('alert',type:'info',message:'This record is associated with another data. You cannot delete it !!');
            }else
            {
                $this->dispatch('alert',type:'error',message:'Failed To Delete Assigned Subject Data !!');
            }
        }
    }

    public function softdelete(Exampanel $exampanel_faculty)
    {
        DB::beginTransaction();

        try
        {
            $exampanel_faculty->delete();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Faculty Soft Deleted From Exam Panel Successfully !!');

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Failed To Soft Delete Assigned Subject !!');
        }
    }

    public function restore($exampanel_faculty_id)
    {
        DB::beginTransaction();

        try
        {
            $exampanel_faculty = Exampanel::withTrashed()->findOrFail($exampanel_faculty_id);

            $exampanel_faculty->restore();

            DB::commit();

            $this->dispatch('alert',type:'success',message:'Exam Panel Faculty Restored Successfully !!');

        }
        catch (\Exception $e)
        {
            DB::rollBack();

            $this->dispatch('alert',type:'error',message:'Exam Panel Faculty Not Found !!');
        }
    }

    public function deleteconfirmation($id)
    {
        $this->delete_id=$id;
        $this->dispatch('delete-confirmation');
    }

    public function view(Exampanel $exampanel)
    {
        if ($exampanel){
            $this->patternclass_id = (isset($exampanel->subject->patternclass->pattern->pattern_name) ? $exampanel->subject->patternclass->pattern->pattern_name : '') . ' ' .
            (isset($exampanel->subject->patternclass->courseclass->classyear->classyear_name) ? $exampanel->subject->patternclass->courseclass->classyear->classyear_name : '') . ' ' .
            (isset($exampanel->subject->patternclass->courseclass->course->course_name) ? $exampanel->subject->patternclass->courseclass->course->course_name : '');
            $this->subject_id = isset($exampanel->subject->subject_name) ? $exampanel->subject->subject_name : '';
            $this->post_id = isset($exampanel->examorderpost->post_name) ? $exampanel->examorderpost->post_name : '';
            $this->department_id = isset($exampanel->faculty->department->dept_name) ? $exampanel->faculty->department->dept_name : '';
            $this->faculty_id = isset($exampanel->faculty->faculty_name) ? $exampanel->faculty->faculty_name : '';
        }else{
            $this->dispatch('alert',type:'error',message:'Exam Panel Details Not Found !!');
        }
        $this->setmode('view');
    }

    public function render()
    {
        $auth_faculty = auth('faculty')->user();

        $appointed_subjects = Hodappointsubject::where('faculty_id', $auth_faculty->id)->where('status', 1)->pluck('subject_id')->toArray();
        $patternclass_id = Subject::whereIn('id', $appointed_subjects)->pluck('patternclass_id')->toArray();
        $this->pattern_classes = Classview::select('id','classyear_name', 'course_name', 'pattern_name')->whereIn('id', $patternclass_id)->where('status',1)->get();
        $this->posts=Examorderpost::select('id','post_name')->where('status',1)->get();

        $this->subjects = Subject::whereIn('id', $appointed_subjects)->select('id', 'subject_name')->where('patternclass_id', $this->patternclass_id)->where('status', 1)->get();

        $this->departments = Department::select('id','dept_name')->where('id',$auth_faculty->department_id)->where('status',1)->get();

        $this->faculties = Faculty::select('id','faculty_name')->where('department_id', $this->department_id)->where('active',1)->whereNotNull('department_id')->get();

        $examPanels = Exampanel::with('subject', 'faculty', 'examorderpost')->withTrashed()->whereIn('subject_id', $appointed_subjects)->paginate($this->perPage);
        $groupedExamPanels = $examPanels->groupBy('subject_id');

        return view('livewire.faculty.exam-panel.view-exam-panel', compact('groupedExamPanels','examPanels'))->extends('layouts.faculty')->section('faculty');
    }
}
