<?php

namespace App\Livewire\Faculty\Home;

use Livewire\Component;

class FacultyHome extends Component
{
    public function render()
    {
        return view('livewire.faculty.home.faculty-home')->extends('layouts.faculty')->section('faculty');
    }
}
