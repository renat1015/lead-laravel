<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['currency_id', 'delivery_price'];
    
    public function currency() {
        return $this->belongsTo(Currency::class);
    }
    
    public function leads() {
        return $this->hasMany(Lead::class);
    }
}
