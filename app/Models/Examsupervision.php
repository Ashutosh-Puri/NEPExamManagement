<?php

namespace App\Models;

use App\Models\Exam;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Examsession;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Examsupervision extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='examsupervisions';
    protected $fillable=[
        'faculty_id',
        'supervision_date',
        'user_id',
        'exam_id',
        'examsession_id',
        'adjustfaculty_id',
        'email_status',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }

    public function examsession(): BelongsTo
    {
        return $this->belongsTo(Examsession::class, 'examsession_id', 'id');
    }

    public function adjustfaculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'adjustfaculty_id', 'id');
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'id');
    }
}
