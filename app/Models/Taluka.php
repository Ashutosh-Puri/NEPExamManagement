<?php

namespace App\Models;

use App\Models\Village;
use App\Models\District;
use App\Models\Studentaddress;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Taluka extends Model
{
    use HasFactory;
    protected $table='talukas';
    protected $fillable=[
        'taluka_code',
        'taluka_name',
        'district_id',
    ];

    public function villages():HasMany
    {
        return $this->hasMany(Village::class,'taluka_id','id');
    }

    public function district(): BelongsTo
    {
        return $this->belongsTo(District::class, 'district_id', 'id');
    }

    public function studentaddress():HasMany
    {
        return $this->HasMany(Studentaddress::class, 'taluka_id', 'id');
    }

    public function getStateColorAttribute(){
     
        return    [
            'U'=>'green',
            'S'=>'blue',
        ][$this->state_or_UT]??'gray' ;
    }
    
    public function getCreatedDateFormatAttribute(){
        return $this->created_at->format('M, d Y');
    }
}
