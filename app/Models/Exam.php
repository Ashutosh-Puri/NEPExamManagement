<?php

namespace App\Models;

use App\Models\Month;
use App\Models\Exampanel;
use App\Models\Examsession;
use App\Models\Academicyear;
use App\Models\Exambuilding;
use App\Models\Papersubmission;
use App\Models\Exampatternclass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exam extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='exams';
    protected $fillable=[
        'exam_name',
        'month',
        'status',
        'academicyear_id',
        'exam_sessions'
    ];

    public function academicyear(): BelongsTo
    {
        return $this->belongsTo(Academicyear::class,'academicyear_id','id')->withTrashed();
    }
   
    public function examsessions()
    {
        return $this->hasMany(Examsession::class,'exam_id','id');
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('exam_name', 'like', "%{$search}%")->orWhere('month', 'like', "%{$search}%");
    }

    public function exampatternclasses()
    {
        return $this->hasMany(Exampatternclass::class)->withTrashed();
    }
    
    public function exampanels()
    {
        return $this->hasMany(Exampanel::class)->withTrashed();
    }
    
    public function papersubmissions()
    {
        return $this->hasMany(Papersubmission::class)->withTrashed();
    }

    public function exambuildings()
    {
        return $this->hasMany(Exambuilding::class,'exam_id','id');
    }
}
