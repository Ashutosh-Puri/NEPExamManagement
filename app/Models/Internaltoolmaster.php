<?php

namespace App\Models;

use App\Models\Internaltoolview;
use App\Models\Internaltooldocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Internaltoolmaster extends Model
{
    use HasFactory,SoftDeletes;
    protected $table='internaltoolmasters';
    protected $fillable=[
        'toolname',
        'course_type',
        'status',
    ];

    public function internaltoolview():HasMany
    {
        return $this->hasMany(Internaltoolview::class,'internaltoolmaster_id','id')->withTrashed();
    }

    public function internaltooldocuments():HasMany
    {
        return $this->hasMany(Internaltooldocument::class,'internaltoolmaster_id','id');
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('toolname', 'like', "%{$search}%");
            
    }
}
