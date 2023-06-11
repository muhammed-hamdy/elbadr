<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'user_id',
        'receiver_id',
        'product_id',
        'amount',
        'status',
    ];

    protected $dates = [
        'created_at', 'updated_at'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    public function getOrderTypeAttribute()
    {
        if($this->receiver_id) {
            return 'transfer balance ' . $this->amount .' from ' . $this->user?->name . ' to ' . $this->receiver?->name;
        }

        if($this->product_id) {
            return 'buying product ' . $this->product->title . ' for '. $this->amount .' from stock';
        }

        if($this->user_id) {
            return 'balance request with amount ' . $this->amount;
        }
    }

    public function balanceBefore($user_id)
    {
        $transaction = $this->transactions->where('user_id', $user_id)->first();

        return $transaction ? $transaction->balance_before : null;
    }

    public function balanceAfter($user_id)
    {
        $transaction = $this->transactions->where('user_id', $user_id)->first();

        return $transaction ? $transaction->balance_after : null;
    }
}
