<?php

namespace App\Models;

use App\Models\Exampatternclass;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Classview extends Model
{
    use HasFactory;
    protected $table = 'class_view';
    protected $guarded=[];

     public function exampatternclasses(): HasMany
    {
        return $this->hasMany(Exampatternclass::class, 'patternclass_id', 'id');
    }
}
