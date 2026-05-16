<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = ['order_id', 'medicine_id', 'quantity', 'price', 'weight'];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}