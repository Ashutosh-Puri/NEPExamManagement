<?php

namespace App\Models;

use App\Models\Examfeecourse;
use App\Models\Applyfeemaster;
use App\Models\Formtypemaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Examfeemaster extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='examfeemasters';
    protected $fillable=[
        'fee_name',
        'remark',
        'default_professional_fee',
        'default_non_professional_fee',
        'form_type_id',
        'apply_fee_id' ,
        'active_status',
        'approve_status',
    ];

    public function formtype(): BelongsTo
    {
        return $this->belongsTo(Formtypemaster::class, 'form_type_id', 'id')->withTrashed();
    }

    public function applyfee(): BelongsTo
    {
        return $this->belongsTo(Applyfeemaster::class, 'apply_fee_id', 'id')->withTrashed();
    }

    public function examfeecourses()
    {
        return $this->hasMany(Examfeecourse::class, 'examfees_id', 'id');
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->with('formtype:form_name,id','applyfee:name,id')->where(function ($subquery) use ($search) {
            $subquery->where('fee_name', 'like', "%{$search}%")
            ->orWhereHas('formtype', function ($subQuery) use ($search) { $subQuery->where('form_name', 'like', "%{$search}%"); })
            ->orWhereHas('applyfee', function ($subQuery) use ($search) { $subQuery->where('name', 'like', "%{$search}%"); });
        });
    }

}
