<?php

namespace App\Models;

use App\Models\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Roletype extends Model
{
    use HasFactory, SoftDeletes;

    protected $table='roletypes';
    protected $fillable = [
    'roletype_name',
    'status',
    ];

    public function roles()
    {
        return $this->hasMany(Role::class,'roletype_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('roletype_name', 'like', "%{$search}%");
    }
}
