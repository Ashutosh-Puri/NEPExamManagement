<?php

namespace App\Models;

use App\Models\Faculty;
use App\Models\Subject;
use App\Models\Internaltoolmaster;
use App\Models\Documentacademicyear;
use App\Models\Facultyinternaldocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facultysubjecttool extends Model
{
    use HasFactory;

    protected $table="facultysubjecttools";
    protected $fillable=[
        'academicyear_id',
        'faculty_id',
        'subject_id',
        'internaltoolmaster_id',
        'departmenthead_id',
        'freeze_by_faculty',
        'freeze_by_hod',
        'verifybyfaculty_id',
        'status',
    ];


    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'id');
    }

    public function academicyear(): BelongsTo
    {
        return $this->belongsTo(Documentacademicyear::class, 'academicyear_id', 'id');
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id', 'id');
    }

    public function departmenthead(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'departmenthead_id', 'id');
    }

    public function verifybyfaculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'verifybyfaculty_id', 'id');
    }

    public function internaltoolmaster(): BelongsTo
    {
        return $this->belongsTo(Internaltoolmaster::class, 'internaltoolmaster_id', 'id');
    }

    public function facultysubjecttools(): HasMany
    {
        return $this->hasMany(Facultyinternaldocument::class, 'facultysubjecttool_id', 'id');
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->with('faculty:faculty_name,id', 'subject')
            ->WhereHas('faculty', function ($query) use ($search) {
                $query->where('faculty_name', 'like', "%{$search}%");
            })
            ->orWhereHas('subject:subject_name,id', function ($query) use ($search) {
                $query->where('subject_name', 'like', "%{$search}%");
            });
    }
}
