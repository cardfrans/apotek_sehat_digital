<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'prescription_id',
        'total_price',
        'shipping_cost',
        'status',
        'privacy_packaging',
        'address',
        'province',
        'city',
        'courier',
        'tracking_number',
        'weight'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function prescription()
    {
        return $this->belongsTo(Prescription::class);
    }

    // RELASI BARU: Membaca multi-produk yang dibeli mandiri oleh user via keranjang
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}