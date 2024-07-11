<?php

namespace App\Models;

use App\Models\Exam;
use App\Models\User;
use App\Models\Faculty;
use App\Models\Subject;
use App\Models\Exambarcode;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Paperassesment extends Model
{
    use HasFactory;

    protected  $guarded=[];

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function userverify()
    {
        return $this->belongsTo(User::class, 'verified_by', 'id');
    }

    public function examiner()
    {
        return $this->belongsTo(Faculty::class,'examinerfaculty_id','id');
    }

    public function moderator()
    {
        return $this->belongsTo(Faculty::class,'moderatorfaculty_id','id');
    }

    public function exambarcodes()
    {
        return $this->hasMany(Exambarcode::class,'paperassesment_id','id');
    }

    public function subject()
    {
       
        return $this->belongsTo(Subject::class,'subject_id','id');
    }
    
    public function userbillby()
    {
        return $this->belongsTo(User::class, 'billcreated_by', 'id');
    }
}
