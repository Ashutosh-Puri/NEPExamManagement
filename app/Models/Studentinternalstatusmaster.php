<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studentinternalstatusmaster extends Model
{
    use HasFactory;
    protected $table= 'studentinternalstatusmaster';
    protected $fillable=[
        'name', // Present, Absent, Copy-Case
        'short_form', // P, A, CC
        'is_active', // 0-Active 1-In-active
    ];
}
