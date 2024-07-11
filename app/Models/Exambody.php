<?php

namespace App\Models;

use App\Models\Role;
use App\Models\User;
use App\Models\College;
use App\Models\Faculty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Exambody extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='exambodies';
    protected $fillable=[
        'faculty_id',
        'role_id',
        'user_id',
        'college_id',
        'profile_photo_path',
        'sign_photo_path',
        'is_active',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class, 'faculty_id', 'id');
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class, 'college_id', 'id');
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query
        ->with('faculty:faculty_name,id','user:name,id')
        ->where(function ($query) use ($search) { 
            $query->orWhereHas('faculty', function ($subQuery) use ($search) {
                $subQuery->where('faculty_name', 'like', "%{$search}%");
            })
            ->orWhereHas('user', function ($subQuery) use ($search) {
                $subQuery->where('name', 'like', "%{$search}%");
            });
        });
    }
}
