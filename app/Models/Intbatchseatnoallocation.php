<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Internalmarksbatch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Intbatchseatnoallocation extends Model
{
    use HasFactory;
    protected $table="intbatchseatnoallocations";
    protected $fillable=[
        'intbatch_id',
        'student_id',
        'seatno',
        'marks',
        'grade',
        'status',
    ];

    public function internalmarksbatch()
    {
        return $this->belongsTo(Internalmarksbatch::class,'intbatch_id','id');
    }

    public function student()
    {
        return $this->belongsTo(Student::class,'student_id','id');
    }
}
