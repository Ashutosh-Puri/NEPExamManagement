<?php

namespace App\Models;

use App\Models\Student;
use App\Models\Boarduniversity;
use App\Models\Educationalcourse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Studentpreviousexam extends Model
{
    use HasFactory;

    protected $table= 'studentpreviousexams';
    protected $fillable=[        
        'studentpreviousexams',
        'educationalcourse_id',
        'passing_year',
        'seat_number',
        'obtained_marks',
        'total_marks',
        'percentage',
        'cgpa',
        'grade',
        'student_id',
        'boarduniversity_id',
    ];

    protected $casts = [
        'passing_year' => 'datetime:Y-m-d',
    ];

    public function student():BelongsTo
    {
        return $this->belongsTo(Student::class,'student_id','id')->withTrashed();
    }

    public function boarduniversity():BelongsTo
    {
        return $this->belongsTo(Boarduniversity::class,'boarduniversity_id','id')->withTrashed();
    }

    public function educationalcourse():BelongsTo
    {
        return $this->belongsTo(Educationalcourse::class,'educationalcourse_id','id')->withTrashed();
    }
}

