<?php

namespace App\Models;

use App\Models\Pattern;
use App\Models\Department;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departmentprefix extends Model
{
    use HasFactory,SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='department_prefixes';
    protected $fillable=[
        'dept_id',
        'pattern_id',
        'prefix',
        'postfix',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class,'dept_id','id')->withTrashed();
    }

    public function pattern(): BelongsTo
    {
        return $this->belongsTo(Pattern::class,'pattern_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->with('pattern:pattern_name,id', 'department:dept_name,id')
        ->where(function ($query) use ($search) {
            $query->where('prefix', 'like', "%{$search}%")
            ->orWhereHas('pattern', function ($subQuery) use ($search) {
                $subQuery->where('pattern_name', 'like', "%{$search}%");
            })
            ->orWhereHas('department', function ($subQuery) use ($search) {
                $subQuery->where('dept_name', 'like', "%{$search}%");
            });
        });
    }
}