<?php

namespace App\Models;

use App\Models\Role;
use App\Models\User;
use App\Models\Faculty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facultyrole extends Model
{
    use HasFactory;
    protected $table="facultyroles";
    protected $fillable = [
        'faculty_id',
        'user_id',
        'role_id',
        'status',
    ];
    

    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id')->withTrashed();
    }

    public function role()
    {
        return $this->belongsTo(Role::class,'role_id','id')->withTrashed();
    }
}
