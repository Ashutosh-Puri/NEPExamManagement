<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Studentadmission extends Model
{
    use HasFactory;
    protected $table='studentadmissions';
    protected $fillable=[
        'student_id',
        'patternclass_id',
        'academicyear_id',
        'status',
        'college_id',
    ];
}
