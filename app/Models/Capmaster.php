<?php

namespace App\Models;

use App\Models\Exam;
use App\Models\College;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Capmaster extends Model
{
    use HasFactory ,SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='capmasters';
    protected $fillable=[
        'cap_name',
        'place',
        'exam_id',
        'status',
        'college_id',
    ];


    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class, 'college_id', 'id')->withTrashed();
    }

    public function exam(): BelongsTo
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id')->withTrashed();
    }


    public function scopeSearch(Builder $query, string $search)
    {
        return $query
        ->with('exam:exam_name,id')
        ->where(function ($query) use ($search) {
            $query->where('cap_name', 'like', "%{$search}%")
            ->orWhereHas('exam', function ($subQuery) use ($search) {
                $subQuery->where('exam_name', 'like', "%{$search}%");
            });
                
        });
    }
}
