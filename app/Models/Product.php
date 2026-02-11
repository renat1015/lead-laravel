<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = ['price', 'delivery_id'];
    
    public function delivery() {
        return $this->belongsTo(Delivery::class);
    }
    
    public function leads() {
        return $this->hasMany(Lead::class);
    }
}
