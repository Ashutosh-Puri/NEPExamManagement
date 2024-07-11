<?php

namespace App\Livewire;

use App\Models\Notice;
use App\Models\College;
use Livewire\Component;
use Livewire\WithPagination;

class HomeIndex extends Component
{   
    use WithPagination;
    public function render()
    {   
        $guest_notices=Notice::where('is_active',1)->whereIn('type', [0, 4])->paginate(10);
        $college=College::where('status',1)->where('is_default',1)->first();
        return view('livewire.home-index',compact('guest_notices','college'))->extends('layouts.guest')->section('guest');
    }
}
