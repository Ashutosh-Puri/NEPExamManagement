<?php

namespace App\Models;

use App\Models\User;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\Exampatternclass;
use Illuminate\Database\Eloquent\Model;
use App\Models\Intbatchseatnoallocation;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Internalmarksbatch extends Model
{
    use HasFactory;
    protected $table="internalmarksbatches";
    protected $fillable=[
        'exam_patternclasses_id',
        'subject_id',
        'subject_type',
        'faculty_id',
        'status',   // 1 batch create, 4 insert into studentmarks with NULL marks,2 finish all marks to preview and confirm //3 preview marks // 5 confirmed marks no change
        'totalMarksentry',
        'amount',
        'billcreated_by',
        'bill_date',
        'totalBatchsize',
        'totalAbsent'
    ];

    public function faculty()
    {
        return $this->belongsTo(Faculty::class,'faculty_id','id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class,'subject_id','id');
    }

    public function exam_patternclass()
    {
        return $this->belongsTo(Exampatternclass::class,'exam_patternclasses_id','id');
    }

    public function intbatchseatnoallocations()
    {
        return $this->hasMany(Intbatchseatnoallocation::class,'intbatch_id','id');
    }

    // public function getstudentdata()
    // {
    //     $intbatches=$this->intbatchseatnoallocations;
    //     $studdata=collect();$count=0;
    //     foreach ($intbatches as $data )
    //     {
    //     if(is_null($data->students->studentmarks->last()))
    //     {
    //         $studdata->push($data); $count++;
    //     }
    //     }
    //     return($studdata->take(15));
    // }
    // public function internalbill()
    // {
    //     return $this->hasOne(Internalbill::class,'internalmarksbatches_id','id');
    // }
    
    public function user()
    {
        return $this->belongsTo(User::class,'billcreated_by','id');
    }

}
