<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banknamemaster extends Model
{
    use HasFactory;
    protected $table='banknamemasters';
    protected $fillable=[
        'bank_name',
        'bank_shortform',
        'is_active',
    ];
}
