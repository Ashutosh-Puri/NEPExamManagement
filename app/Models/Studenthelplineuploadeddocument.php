<?php

namespace App\Models;

use App\Models\Studenthelpline;
use App\Models\Studenthelplinedocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Studenthelplineuploadeddocument extends Model
{
    use HasFactory , SoftDeletes;
    protected $table= 'student_helpline_uploaded_documents';
    protected $dates = ['deleted_at'];
    protected $fillable=[
        'student_helpline_id',
        'helpline_document_id',
        'helpline_document_path',
    ];
    
    public function studenthelpline(): BelongsTo
    {
        return $this->belongsTo(Studenthelpline::class, 'student_helpline_id', 'id')->withTrashed();
    }

    public function studenthelplinedocument(): BelongsTo
    {
        return $this->belongsTo(Studenthelplinedocument::class, 'helpline_document_id', 'id')->withTrashed();
    }

}
