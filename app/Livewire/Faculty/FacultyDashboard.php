<?php

namespace App\Livewire\Faculty;

use Livewire\Component;

class FacultyDashboard extends Component
{
    public function render()
    {
        return view('livewire.faculty.faculty-dashboard')->extends('layouts.faculty')->section('faculty');
    }
}
