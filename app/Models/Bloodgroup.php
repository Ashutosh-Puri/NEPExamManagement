<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bloodgroup extends Model
{
    use HasFactory;
    
    protected $table='bloodgroups';
    protected $fillable=[
        'bloodgroup',
        'is_active',
    ];
}
