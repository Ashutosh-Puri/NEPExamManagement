<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gendermaster extends Model
{
    use HasFactory;
    protected $table='gendermasters';
    protected $fillable=[
        'gender',
        'gender_shortform',
        'is_active',
    ];

}
