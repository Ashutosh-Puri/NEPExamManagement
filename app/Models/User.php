<?php

namespace App\Models;

use App\Models\Role;
use App\Models\College;
use App\Models\Subject;
use App\Models\Department;
use App\Models\Studenthelpline;
use App\Models\Hodappointsubject;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Jobs\User\SendEmailVerificationNotificationJob;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\User\UserRegisterMailNotification;
use App\Notifications\User\UserResetPasswordNotification;

class User extends Authenticatable  implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable,SoftDeletes;
    protected $dates = ['deleted_at'];

    public function sendPasswordResetNotification($token)
    {
        $this->notify(new UserResetPasswordNotification($token));
    }

    public function sendEmailVerificationNotification()
    {
        SendEmailVerificationNotificationJob::dispatch($this);
    }

    protected $guard="user";

    protected $fillable = [
        'name',
        'email',
        'password',
        'user_contact_no',
        'college_id',
        'department_id',
        'is_active',
        'role_id',
    ];

    protected $encodedColumns = [
        'user_contact_no',
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

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class,'college_id','id')->withTrashed();
    }

    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class,'role_id','id')->withTrashed();
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class,'department_id','id')->withTrashed();
    }

    public function hodappointsubjects():HasMany
    {
        return $this->hasMany(Hodappointsubject::class,'appointby_id','id')->withTrashed();
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('name', 'like', "%{$search}%")
        ->orWhere('email', 'like', "%{$search}%");

    }
}
