<?php

namespace App\Models;

use App\Models\Exam;
use App\Models\Student;
use App\Models\Transaction;
use App\Models\Patternclass;
use App\Models\Ordinace163master;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Studentordinace163 extends Model
{
    use HasFactory;
    protected $table="studentordinace163s";
    protected $fillable=[
        'seatno',
        'student_id',
        'patternclass_id',
        'exam_id',       
        'ordinace163master_id',
        'marks',
        'marksused',
        'status',
        'is_applicable',
        'fee',
        'payment_date',
        'is_fee_paid',
        'transaction_id'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id', 'id');
    }

    public function exam()
    {
        return $this->belongsTo(Exam::class, 'exam_id', 'id');
    }

    public function patternclass()
    {
        return $this->belongsTo(Patternclass::class, 'patternclass_id', 'id');
    }
    
    public function ordinace163master()
    {
        return $this->belongsTo(Ordinace163master::class, 'ordinace163master_id', 'id');
    }

    public function transaction()
    {
      return $this->belongsTo(Transaction::class, 'transaction_id', 'id');
    }
    
    public function scopeSearch(Builder $query,string $search)
    {
        return $query->with('ordinace163master:activity_name,id','exam:exam_name,id','student:student_name,id')
        ->where(function ($subquery) use ($search) {
            
            $subquery->orWhereHas('ordinace163master', function ($subQuery) use ($search) {
                    $subQuery->where('activity_name', 'like', "%{$search}%");
            })->orWhereHas('exam', function ($subQuery) use ($search) {
                $subQuery->where('exam_name', 'like', "%{$search}%");
            })->orWhereHas('student', function ($subQuery) use ($search) {
                $subQuery->where('student_name', 'like', "%{$search}%");    
            });
        });
    }
}