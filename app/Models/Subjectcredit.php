<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subjectcredit extends Model
{
    use HasFactory, SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table='subjectcredits';
    protected $fillable=[
        'credit',
        'marks',
        'passing',
    ];
    
    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('credit', 'like', "%{$search}%")->orWhere('passing', 'like', "%{$search}%");

    }
}
