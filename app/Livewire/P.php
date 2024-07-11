<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Patternclass;
use Livewire\WithPagination;

class P extends Component
{   
    use WithPagination;

    // public $ok=[];
    public function render()
    {   
        $pattern_classes=Patternclass::paginate(10);
        return view('livewire.p',compact('pattern_classes'));
    }
}
