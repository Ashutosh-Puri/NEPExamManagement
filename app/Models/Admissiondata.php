<?php

namespace App\Models;

use App\Models\User;
use App\Models\College;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Department;
use App\Models\Academicyear;
use App\Models\Patternclass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Admissiondata extends Model
{
    use HasFactory ,SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='admissiondatas';
    protected $fillable=[
        'memid',
        'stud_name',
        'user_id',
        'patternclass_id',
        'subject_id',
        'academicyear_id',
        'college_id',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id')->withTrashed();
    }

    public function academicyear(): BelongsTo
    {
        return $this->belongsTo(Academicyear::class, 'academicyear_id', 'id')->withTrashed();
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class, 'college_id', 'id')->withTrashed();
    }

    public function patternclass(): BelongsTo
    {
        return $this->belongsTo(Patternclass::class, 'patternclass_id', 'id')->withTrashed();
    }

    public function checkexamform()
    {
       $stud= Student::where('memid',$this->memid)->whereNotNull('email_verified_at')->orderBy('updated_at','DESC')->get();
        
        if(!is_null($stud))
        {
            if($stud->count()>0)
            {   
                if($stud->first()->checkstudexamform($stud->first()->id))
                {
                    return 'Filled';
                } 
                else
                {
                    return 'Not Filled';
                }
            }
            else
            {
                return 'Not Registered';  
            }
        }
        else
        {
            return 'Not Registered';
        }
    }


    public function scopeSearch(Builder $query, string $search)
    {
        return $query->where('memid', 'like', "%{$search}%")->orWhere('stud_name', 'like', "%{$search}%");
    }
}
