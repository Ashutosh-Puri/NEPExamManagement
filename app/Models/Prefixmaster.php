<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prefixmaster extends Model
{
    use HasFactory;
    protected $table='prefixmasters';
    protected $fillable=[
        'prefix',
        'prefix_shortform',
        'is_active',
    ];
}
