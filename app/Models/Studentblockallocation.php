<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Blockallocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Studentblockallocation extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='studentblockallocations';
    protected $fillable=[
        'bloackallocation_id',
        'student_id',
        'seatno',
    ];


    public function blockallocation()
    {
        return $this->belongsTo(Blockallocation::class,'bloackallocation_id','id')->withTrashed();
    }
    public function student()
    {
        return $this->belongsTo(Student::class,'student_id','id');
    }
    

}
