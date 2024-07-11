<?php

namespace App\Models;

use App\Models\Studenthelplinequery;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Studenthelplinedocument extends Model
{
    use HasFactory , SoftDeletes;
    protected $table= 'student_helpline_documents';
    protected $dates = ['deleted_at'];
    protected $fillable=[
        'document_name',
        'is_required',
        'is_active',
        'student_helpline_query_id',
    ];

    public function studenthelplinequery(): BelongsTo
    {
        return $this->belongsTo(Studenthelplinequery::class, 'student_helpline_query_id', 'id')->withTrashed();
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->with('studenthelplinequery:query_name,id')->where(function ($subquery) use ($search) {
            $subquery->where('document_name', 'like', "%{$search}%")
                ->orWhereHas('studenthelplinequery', function ($subQuery) use ($search) {
                    $subQuery->where('query_name', 'like', "%{$search}%");
                }
            );
        });
    }
}
