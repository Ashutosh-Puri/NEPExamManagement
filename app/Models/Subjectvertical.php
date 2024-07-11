<?php

namespace App\Models;

use App\Models\Subject;
use App\Models\Subjectbucket;
use App\Models\Subjectbuckettypemaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subjectvertical extends Model
{
    use HasFactory,SoftDeletes;
    protected $dates = ['deleted_at'];
    protected $table="subjectverticals";
    protected $fillable = [
        'subject_vertical',
        'subjectvertical_shortname',
        'subjectbuckettype_id',
        'is_active',
    ];

    public function subjects():HasMany
    {
        return $this->hasMany(Subject::class,'subjectvertical_id','id')->withTrashed();
    }
    public function subjectbuckets():HasMany
    {
        return $this->hasMany(Subjectbucket::class,'subjectvertical_id','id')->withTrashed();
    }
    public function buckettype()
    {
        return $this->belongsTo(Subjectbuckettypemaster::class, 'subjectbuckettype_id','id');
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->where('subject_vertical', 'like', "%{$search}%")
        ->orWhere('subjectvertical_shortname', 'like', "%{$search}%");
    }
}
