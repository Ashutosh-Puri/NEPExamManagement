<?php

namespace App\Models;

use App\Models\Country;
use App\Models\District;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class State extends Model
{
    use HasFactory;
    protected $table= 'states';
    protected $fillable=[
        'state_code',
        'state_name',
        'census_code',
        'state_or_UT',
        'country_id',
    ];

    public function distrits():HasMany
    {
        return $this->hasMany(District::class,'state_id','id');
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
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
