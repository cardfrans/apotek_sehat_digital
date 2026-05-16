<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['user_id', 'medicine_id', 'quantity'];

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}