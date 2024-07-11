<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Examorderpost extends Model
{
    use HasFactory,SoftDeletes; 
    protected $dates = ['deleted_at'];
    protected $table='exam_order_posts';
    protected $fillable=[
        'post_name',
        'status'
    ];

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('post_name', 'like', "%{$search}%");
    }
}
