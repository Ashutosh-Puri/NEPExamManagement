<?php

namespace App\Models;

use App\Models\Instruction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Instructiontype extends Model
{
    use HasFactory ,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='instructiontypes';

    protected $fillable=[
        'instruction_type',
        'is_active'
    ];


    public function instructions()
    {
        return $this->hasMany(Instruction::class, 'instructiontype_id', 'id');
    }


    public function scopeSearch(Builder $query, string $search)
    {
        return $query->where('instruction_type', 'like', "%{$search}%");
               
    }
}
