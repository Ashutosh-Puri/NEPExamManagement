<?php

namespace App\Models;

use App\Models\Taluka;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Village extends Model
{
    use HasFactory;
    protected $table='villages';
    protected $fillable=[      
        'village_code',
        'village_name',
        'village_name_local',
        'taluka_id',
    ];

    public function taluka(): BelongsTo
    {
        return $this->belongsTo(Taluka::class, 'taluka_id', 'id');
    }
    
    public function getCreatedDateFormatAttribute(){
        return $this->created_at->format('M, d Y');
    }
}
