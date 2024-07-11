<?php

namespace App\Models;

use App\Models\Department;
use App\Models\Departmenttype;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Departmenttypes extends Model
{
    use HasFactory;

    protected $table='department_types';
    protected $guarded;


    public function department():BelongsTo
    {
        return $this->belongsTo(Department::class,'department_id','id');
    }

    public function departmenttype():BelongsTo
    {
        return $this->belongsTo(Departmenttype::class,'departmenttype_id','id');
    }


   
}
