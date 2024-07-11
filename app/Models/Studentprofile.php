<?php

namespace App\Models;

use App\Models\Caste;
use App\Models\Student;
use App\Models\Castecategory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Studentprofile extends Model
{
    use HasFactory;
    protected $table='studentprofiles';
    protected $fillable = [
        'student_name_devnagari',
        'student_name_on_adharcard',
        'mother_name_devnagari',
        'father_name',
        'father_name_devnagari',
        'parent_name',
        'parent_mobile_no',
        'title',
        'gender',
        'date_of_birth',
        'date_of_birth_on_adharcard',
        'nationality',
        'domicile',
        'caste_id',      
        'caste_category_id',
        'is_noncreamylayer',
        'is_minority',
        'is_handicap',
        'maritalstatus_id',    
        'migratestud',
        'profile_photo_path',
        'sign_photo_path',
        'student_id',       
        'profile_complete_status',
    ];


    protected $encodedColumns = [
        'student_name_devnagari',
        'student_name_on_adharcard',
        'mother_name_devnagari',
        'father_name',
        'father_name_devnagari',
        'parent_name',
        'parent_mobile_no',
    ];
    
    public function setAttribute($key, $value)
    {
        if (in_array($key, $this->encodedColumns)) {
            return $this->setEncodedAttribute($key, $value);
        } else {
            return parent::setAttribute($key, $value);
        }
    }

    protected function setEncodedAttribute($key, $value)
    {
        return parent::setAttribute($key, base64_encode($value));
    }

    public function getAttribute($key)
    {
        $value = parent::getAttribute($key);

        if (in_array($key, $this->encodedColumns) && $value !== null) {
            return base64_decode($value);
        }

        return $value;
    }

    public function student():BelongsTo
    {
        return $this->belongsTo(Student::class,'student_id','id')->withTrashed();
    }

    public function castecategory():BelongsTo
    {
        return $this->belongsTo(Castecategory::class,'caste_category_id','id');
    }

    public function caste():BelongsTo
    {
        return $this->belongsTo(Caste::class,'caste_id','id');
    }
}
