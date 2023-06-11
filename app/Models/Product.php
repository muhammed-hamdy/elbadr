<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'price',
        'stock_amount',
        'status',
    ];

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function users()
    {
        return $this->hasManyThrough(User::class, Order::class);
    }

    public function getStockAvailabilityAttribute()
    {
        return $this->stock_amount > 0;
    }

    public function scopeActive($query)
    {
        return $this->where('status', 1);
    }

}
