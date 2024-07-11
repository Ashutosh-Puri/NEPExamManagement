<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Extracreditsubjectexamform;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Extracreditsubject extends Model
{
    use HasFactory , SoftDeletes;
    protected $dates = ['deleted_at'];
    
    protected $table="extracreditsubjects";
    public $fillable = [
        'subject_name',
        'subject_code',
        'subject_category',
        'subject_option',
        'subject_type',
        'subject_credit',
        'status',
        'patternclass_id',
        'subject_sem',
        'course_type',
    ];

    // public function studentextracreditexamforms()
    // {
    //     return $this->hasMany(Extracreditsubjectexamform::class,'subject_id','id')->withTrashed();
    // }

    // public function hodappointextracreditsubjects()
    // {
    //     return $this->hasMany(hodappointextracreditsubject::class,'subject_id','id');
    // }

    // public function internalmarksextracreditbatches()
    // {
    //     return $this->hasMany(Internalmarksextracreditbatch::class,'subject_id','id');
    // }
    
    // public function intextracreditbatchseatnoallocations()
    // {
    //     return $this->hasMany(Intextracreditbatchseatnoallocation::class,'subject_id','id');
    // }

    // public function checkexamforms(Extracreditsubject $s,$student_id,$eid)
    // {
        
    //    $p= Extracreditsubjectexamform::where('student_id',$student_id)
    //    ->where('subject_id',$s->id)
    //    ->where('exam_id',$eid);
    //    return $p->count()==0?'P':'*';
    // }
}
