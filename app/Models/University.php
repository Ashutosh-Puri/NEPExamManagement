<?php

namespace App\Models;

use App\Models\College;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class University extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='universities';
    protected $fillable=[
        'university_name',
        'university_address',
        'university_website_url',
        'university_email',
        'university_contact_no',
        'university_logo_path',
        'status',
    ];
     
    public function colleges(): HasMany
    {
        return $this->hasMany(College::class,'university_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('university_name', 'like', "%{$search}%")
        ->orWhere('university_email', 'like', "%{$search}%")
        ->orWhere('university_address', 'like', "%{$search}%")
        ->orWhere('university_website_url', 'like', "%{$search}%")
        ->orWhere('university_contact_no', 'like', "%{$search}%");
    }
}
