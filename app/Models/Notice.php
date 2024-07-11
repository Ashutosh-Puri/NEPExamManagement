<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notice extends Model
{
    use HasFactory , SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='notices';
    protected $fillable=[
        'title',
        'description',
        'start_date',
        'end_date',
        'type',
        'is_active',
        'user_id',
    ];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->withTrashed();
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->with('user:name,id')->where('title', 'like', "%{$search}%")
        ->orWhere('description', 'like', "%{$search}%")
        ->orWhereHas('user', function ($subQuery) use ($search) {
            $subQuery->where('name', 'like', "%{$search}%");
        });
       
    }
}
