<?php

namespace App\Models;

use App\Models\Faculty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Designation extends Model
{
    use HasFactory, SoftDeletes;
    protected $table='designations';
    protected $fillable=[
        'designation_name',
        'status',
    ];

    public function faculties()
    {
        return $this->hasMany(Faculty::class,'faculty_id','id')->withTrashed();
    }
}