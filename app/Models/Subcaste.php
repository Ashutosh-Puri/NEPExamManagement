<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subcaste extends Model
{
    use HasFactory;
    protected $table='subcastes';
    protected $fillable=[
        
        'subcaste_name',
        'caste_id',
        'is_active',
    ];

}
