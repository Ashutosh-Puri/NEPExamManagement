<?php

namespace App\Livewire\User\Observer;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class DBObserver extends Component
{   
    # By Ashutosh
    use WithPagination;

    public function truncate()
    {   
        DB::table('db_changes_log')->truncate();
    }

    public function mount()
    {   
        DB::table('db_changes_log')->truncate();
    }
    
    public function render()
    {   
        $logs = DB::table('db_changes_log')->orderBy('created_at', 'desc')->paginate(100);
        return view('livewire.user.observer.d-b-observer',compact('logs'))->extends('layouts.user')->section('user');
    }
}
