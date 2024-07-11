<?php

namespace App\Models;

use App\Models\Role;
use App\Models\College;
use App\Models\Subject;
use App\Models\Exampanel;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Facultyhead;
use App\Models\Hodappointsubject;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Facultybankaccount;
use App\Models\Internalmarksbatch;
use App\Models\Internaltoolauditor;
use App\Models\Facultyinternaldocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Jobs\Faculty\SendEmailVerificationNotificationJob;
use App\Notifications\Faculty\FacultyRegisterMailNotification;
use App\Notifications\Faculty\FacultyResetPasswordNotification;

class Faculty extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new FacultyResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification()
    {
        SendEmailVerificationNotificationJob::dispatch($this);
    }

    protected $dates=['deleted_at'];
    protected $guard = 'faculty';
    protected $table="faculties";
    protected $fillable = [
        'prefix',
        'faculty_name',
        'email',
        'date_of_birth',
        'gender',
        'category',
        'mobile_no',
        'current_address',
        'permanant_address',
        'pan',
        'active',
        'email_verified_at',
        'password',
        'profile_photo_path',
        'unipune_id',
        'qualification',
        'designation_id',
        'department_id',
        'departmenthead_id',
        'college_id',
        'active',
        'last_login',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];


    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    protected $encodedColumns = [
        'mobile_no',
        'pan',
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


    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class, 'facultyroles', 'faculty_id', 'role_id')->withPivot('status');
    }

    public function designation(): BelongsTo
    {
     return $this->belongsTo(Designation::class,'designation_id','id')->withTrashed();
    }

    public function exampanels()
    {
        return $this->hasMany(Exampanel::class,'faculty_id','id')->withTrashed();
    }

    public function department(): BelongsTo
    {
     return $this->belongsTo(Department::class,'department_id','id')->withTrashed();
    }

    public function college(): BelongsTo
    {
     return $this->belongsTo(College::class,'college_id','id')->withTrashed();
    }

    public function facultybankaccount()
    {
        return $this->hasOne(Facultybankaccount::class,'faculty_id','id')->withTrashed();
    }

    public function facultyhead()
    {
        return $this->hasMany(Facultyhead::class,'faculty_id','id')->withTrashed();
    }

    public function hodappointsubjects()
    {
        return $this->hasMany(Hodappointsubject::class,'faculty_id','id')->withTrashed();
    }

    public function subjects()
    {
        return $this->hasMany(Subject::class, 'faculty_id','id')->withTrashed();
    }

    public function facultyinternaldocument():HasMany
    {
        return $this->hasMany(Facultyinternaldocument::class,'verifybyfaculty_id','id')->withTrashed();
    }

    public function departments(): BelongsToMany
    {
        return $this->belongsToMany(Department::class,'facultyheads','faculty_id', 'department_id','id')
        ->withPivot('status','department_id')
        ->wherePivot('status','1')->withTrashed();
    }

    public function getdepartment($deptid)
    {
        return Department::withTrashed()->where('id',$deptid)->first()->dept_name;
    }

    public function internaltoolauditors()
    {
        return $this->hasMany(Internaltoolauditor::class, 'faculty_id','id')->withTrashed();
    }

    public function internalmarksbatches()
    {
        return $this->hasMany(Internalmarksbatch::class,'faculty_id','id');
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where(function ($subquery) use ($search) {
            $subquery->where('faculty_name', 'like', "%{$search}%");
        });
    }
}
