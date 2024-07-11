<?php

namespace App\Models;

use App\Models\Patternclass;
use App\Models\Examfeemaster;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Examfeeview extends Model
{
    use HasFactory;
    protected $table = 'exam_fee_view';
    protected $guarded=[];

    
    public function patternclass(): BelongsTo
    {
        return $this->belongsTo(Patternclass::class, 'patternclass_id', 'id')->withTrashed();
    }

    public function examfee(): BelongsTo
    {
        return $this->belongsTo(Examfeemaster::class, 'examfees_id', 'id')->withTrashed();
    }
}
