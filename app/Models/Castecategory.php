<?php

namespace App\Models;

use App\Models\Caste;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Castecategory extends Model
{
    use HasFactory;
    protected $table='caste_categories';
    protected $fillable=[
        'caste_category',
        'caste_category_desc',
        'reservation',
        'is_active',  
    ];

    public function castes(): HasMany
    {
        return $this->hasMany(Caste::class,'caste_category_id','id');
    }



}
