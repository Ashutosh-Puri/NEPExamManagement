<?php

namespace App\Models;

use App\Models\Studenthelplinedocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Studenthelplinequery extends Model
{
    use HasFactory , SoftDeletes;
    protected $table= 'student_helpline_queries';
    protected $dates = ['deleted_at'];
    protected $fillable=[
        'query_name',
        'is_active'
    ];

    public function studenthelplinedocuments(): HasMany
    {
        return $this->hasMany(Studenthelplinedocument::class, 'student_helpline_query_id', 'id')->withTrashed();
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('query_name', 'like', "%{$search}%");
    }
}
