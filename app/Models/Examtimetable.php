<?php

namespace App\Models;

use App\Models\Subject;
use App\Models\Exambarcode;
use App\Models\Subjectbucket;
use App\Models\Timetableslot;
use App\Models\Blockallocation;
use App\Models\Exampatternclass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Examtimetable extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='exam_timetables';
    protected $fillable=[
        'subject_id',
        'exam_patternclasses_id',
        'examdate',
        'timeslot_id',
        'subject_sem',
        'status',
    ];

    public function subjectbucket(): BelongsTo
    {
        return $this->belongsTo(Subjectbucket::class, 'subjectbucket_id', 'id')->withTrashed();
    }

    public function exampatternclass(): BelongsTo
    {
        return $this->belongsTo(Exampatternclass::class, 'exam_patternclasses_id', 'id')->withTrashed();
    }
   
    public function timetableslot(): BelongsTo
    {
        return $this->belongsTo(Timetableslot::class, 'timeslot_id','id')->withTrashed();
    }
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id')->withTrashed();
    }

    public function checkblockallocation($timeslot_id)
    {   
        $total=0;      
      
        $examtimetables=Examtimetable::select('id','subject_id','exam_patternclasses_id')->where('examdate',$this->examdate)->where('timeslot_id',$timeslot_id)->where('status',1)->get();

        foreach($examtimetables as $examtimetable)
        { 
            $block_allocation_count=Blockallocation::where('subject_id',$examtimetable->subject_id)->where('exampatternclass_id',$examtimetable->exam_patternclasses_id)->count();
          
            $total= $total + $block_allocation_count;
        }

        return  $total;
    }


    public function checkbarcode($timeslot_id)
    {  
        $total=0;
        $examtimetables=Examtimetable::select('id')->where('examdate',$this->examdate)->where('timeslot_id',$timeslot_id)->where('status',1)->pluck('id');
        $total= $barcodes=Exambarcode::whereIn('exam_timetable_id',$examtimetables)->count();
        return $total;
    }


    public function exambarcodes()
    {
         return $this->hasMany(Exambarcode::class,'exam_timetable_id','id');
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->with( 'exampatternclass.exam:exam_name,id','subject:subject_name,id','timetableslot:timeslot,id')
        ->where('examdate', 'like', "%{$search}%")
        ->orWhere(function ($subquery) use ($search) {
            $subquery->whereHas('exampatternclass.exam', function ($subQuery) use ($search) {
                $subQuery->where('exam_name', 'like', "%{$search}%");
             })->orWhereHas('timetableslot', function ($subQuery) use ($search) {
                 $subQuery->where('timeslot', 'like', "%{$search}%");
            })->orWhereHas('subject', function ($subQuery) use ($search) {
                $subQuery->where('subject_name', 'like', "%{$search}%");
            });
        });

    }
    
}
