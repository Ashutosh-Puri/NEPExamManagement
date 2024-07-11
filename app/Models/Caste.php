<?php

namespace App\Models;

use App\Models\Castecategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Caste extends Model
{
    use HasFactory;
    protected $table='castes';
    protected $fillable=[
        'sno',
        'caste_name',
        'caste_category_id',
        'is_active',
    ];


    public function caste_category(): BelongsTo
    {
        return $this->belongsTo(Castecategory::class, 'caste_category_id', 'id');
    }


}
