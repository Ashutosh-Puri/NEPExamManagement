<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;

class C extends Component
{   
    use WithPagination;
    public $pattern_class;

    public function render()
    {
        return view('livewire.c');
    }
}
