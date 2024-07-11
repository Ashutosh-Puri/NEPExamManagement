<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Addresstype extends Model
{
    use HasFactory;
    protected $table='addresstypes';
    protected $fillable=[
        'type',
        'is_active'

    ];

    
}
