<?php

namespace App\Models;

use App\Models\Exam;
use App\Models\User;
use App\Models\Faculty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Capattendance extends Model
{
    use HasFactory;
    
    protected $table="capattendances";
    protected $fillable=[
        'faculty_id',
        'cap_date',
        'exam_id',
        'emailstatus',
        'user_id',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }
    
    public function faculty()
    {
        return $this->belongsTo(Faculty::class,'faculty_id','id');
    }
    
    public function exam()
    {
        return $this->belongsTo(Exam::class,'exam_id','id');
    }


  }
