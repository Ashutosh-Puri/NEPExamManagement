<?php

namespace App\Models;

use App\Models\Faculty;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facultybankaccount extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="facultybankaccounts";
    protected $fillable = [
        'faculty_id',
        'account_no',
        'bank_address',
        'bank_name',
        'branch_name',
        'branch_code',
        'account_type',
        'ifsc_code',
        'micr_code',
        'acc_verified',
    ];

    protected $encodedColumns = [
        'account_no',
        'ifsc_code',
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

    public function faculty()
    {
        return $this->belongsTo(Faculty::class,'faculty_id','id')->withTrashed();
    }
}
