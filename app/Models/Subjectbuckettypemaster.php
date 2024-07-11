<?php

namespace App\Models;

use App\Models\Subjectvertical;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Subjectbuckettypemaster extends Model
{
    use HasFactory;
    protected $table='subjectbuckettypemaster';
    protected $fillable=[
        'buckettype_name',
    ];

    public function subjectverticals()
    {
        return $this->hasMany(Subjectvertical::class, 'subjectvertical_id','id');
    }
}
