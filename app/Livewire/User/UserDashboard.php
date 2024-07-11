<?php

namespace App\Livewire\User;

use App\Models\Exam;
use App\Models\User;
use App\Models\Course;
use App\Models\Faculty;
use App\Models\Pattern;
use App\Models\Student;
use App\Models\Subject;
use Livewire\Component;
use App\Models\Programme;
use Illuminate\Support\Facades\DB;

class UserDashboard extends Component
{   
    public $user_count=0;
    public $faculty_count=0;
    public $student_count=0;
    public $exam_count=0;
    public $subject_count=0;
    public $programe_count=0;
    public $course_count=0;
    public $pattern_count=0;
    public $login_user_count=0;

    public function mount()
    {
        $this->user_count=User::count();
        $this->faculty_count=Faculty::count();
        $this->student_count=Student::count();
        $this->exam_count=Exam::count();
        $this->subject_count=Subject::count();
        $this->programe_count=Programme::count();
        $this->course_count=Course::count();
        $this->pattern_count=Pattern::count();
        $this->fetch_login_users();
    }
    
    public function fetch_login_users()
    {
        $this->login_user_count=DB::table('sessions')->whereNotNull('user_id')->distinct('user_id')->count('user_id');
    }

    public function render()
    {
        return view('livewire.user.user-dashboard')->extends('layouts.user')->section('user');
    }
}
