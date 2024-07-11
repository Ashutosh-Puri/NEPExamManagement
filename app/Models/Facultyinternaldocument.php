<?php

namespace App\Models;

use App\Models\Faculty;
use App\Models\Subject;
use App\Models\Hodappointsubject;
use App\Models\Facultysubjecttool;
use App\Models\Documentacademicyear;
use App\Models\Internaltooldocument;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Internaltooldocumentmaster;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Facultyinternaldocument extends Model
{
    use HasFactory,SoftDeletes;
    protected $table='facultyinternaldocuments';
    protected $fillable=[
        'facultysubjecttool_id',
        'internaltooldocument_id',
        'document_fileName',
        'document_filePath',
        'verificationremark',
        'status',
    ];

    public function facultysubjecttool()
    {
        return $this->belongsTo(Facultysubjecttool::class,'facultysubjecttool_id','id');
    }

    public function internaltooldocument():BelongsTo
    {
        return $this->belongsTo(Internaltooldocument::class,'internaltooldocument_id','id');
    }

    public function scopeSearch(Builder $query, string $search)
    {
        return $query->with('internaltooldocument.internaltooldocumentmaster:doc_name,id')
        ->whereHas('internaltooldocument.internaltooldocumentmaster:doc_name,id', function ($query) use ($search) {
            $query->where('doc_name', 'like', "%{$search}%");
        });

    }


}
