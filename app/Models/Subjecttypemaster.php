<?php

namespace App\Models;

use App\Models\Subjecttype;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subjecttypemaster extends Model
{
    use HasFactory;
    protected $table='subjecttypemaster';
    protected $fillable=[
        'subjectcategory_id',
        'subjecttype_id',
    ];

    public function subjecttype()
    {
        return $this->belongsTo(Subjecttype::class, 'subjecttype_id','id')->withTrashed();
    }
}
