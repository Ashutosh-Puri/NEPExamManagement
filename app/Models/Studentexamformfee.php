<?php

namespace App\Models;

use App\Models\Examfeemaster;
use App\Models\Examformmaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Studentexamformfee extends Model
{
    use HasFactory;

    protected $table="studentexamformfees";
    protected $fillable = [
    'examformmaster_id',
    'examfees_id',
    'fee_amount'
    ];

    public function examformmaster()
    {         
        return $this->belongsTo(Examformmaster::class,'examformmaster_id','id');

    }
    
    public function examfee()
    {         
        return $this->belongsTo(Examfeemaster::class,'examfees_id','id')->withTrashed();

    }
}
