<?php

namespace App\Livewire\Student\Home;

use Livewire\Component;

class StudentHome extends Component
{
    public function render()
    {
        return view('livewire.student.home.student-home')->extends('layouts.student')->section('student');
    }
}
