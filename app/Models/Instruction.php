<?php

namespace App\Models;

use App\Models\User;
use App\Models\College;
use App\Models\Instructiontype;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Instruction extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='instructions';
    protected $fillable=[
        'instruction_name',
        'instructiontype_id',
        'user_id',
        'college_id',
        'is_active'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class, 'college_id', 'id');
    }

    public function instructiontype(): BelongsTo
    {
        return $this->belongsTo(Instructiontype::class, 'instructiontype_id', 'id');
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->where('instruction_name', 'like', "%{$search}%")->orWhere('instruction_name', 'like', "%{$search}%");     
    }
}
