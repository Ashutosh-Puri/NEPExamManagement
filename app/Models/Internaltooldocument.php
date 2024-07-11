<?php

namespace App\Models;
use App\Models\Internaltoolmaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Internaltooldocumentmaster;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Internaltooldocument extends Model
{
    use HasFactory,SoftDeletes;
    protected $table='internaltooldocuments';
    protected $fillable=[
        'internaltooldoc_id',
        'internaltoolmaster_id',
        'is_multiple',
        'status',
    ];

    public function internaltooldocumentmaster(): BelongsTo
    {
        return $this->belongsTo(Internaltooldocumentmaster::class, 'internaltooldoc_id', 'id');
    }

    public function internaltoolmaster(): BelongsTo
    {
        return $this->belongsTo(Internaltoolmaster::class, 'internaltoolmaster_id', 'id');
    }

    public function scopeSearch(Builder $query, string $search)
    {
                $documentQuery->where('doc_name', 'like', "%{$search}%");
                return $query->with('internaltooldocumentmaster:doc_name,id', 'internaltoolmaster:toolname,id')
        ->where(function ($query) use ($search) {
            $query->whereHas('internaltooldocumentmaster', function ($documentQuery) use ($search) {
                $documentQuery->where('doc_name', 'like', "%{$search}%");
            })
            ->orWhereHas('internaltoolmaster', function ($toolQuery) use ($search) {
                $toolQuery->where('toolname', 'like', "%{$search}%");
            });
        });
    }

}