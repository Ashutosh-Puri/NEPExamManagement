<?php

namespace App\Models;

use App\Models\Examformmaster;
use App\Models\Studentordinace163;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Transaction extends Model
{
    use HasFactory;
    protected $table= 'transactions';
    protected $fillable=[
        'transaction_name',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_refund_id',
        'payment_date',
        'razorpay_signature',
        'amount',
        'status',
    ];

    public function examformmaster(): HasOne
    {
        return $this->hasOne(Examformmaster::class, 'transaction_id', 'id');
    }

    public function studentordinace163(): HasOne
    {
        return $this->hasOne(Studentordinace163::class, 'transaction_id', 'id');
    }

    public function scopeSearch(Builder $query,string $search)
    {
        return $query->where('razorpay_payment_id', 'like', "%{$search}%")
        ->orWhere('razorpay_order_id', 'like', "%{$search}%")
        ->orWhere('razorpay_refund_id', 'like', "%{$search}%")
        ->orWhere('amount', 'like', "%{$search}%");
    }
}
