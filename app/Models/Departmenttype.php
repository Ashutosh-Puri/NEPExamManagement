<?php

namespace App\Models;

use App\Models\Department;
use App\Models\Departmenttypes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departmenttype extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='departmenttypes';
    protected $fillable=[
        'departmenttype',
        'description',
        'status',
    ];

    public function departments()
    {
        return $this->belongsToMany(Department::class, 'department_types', 'department_id', 'departmenttype_id');
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('departmenttype', 'like', "%{$search}%");
    }
}
