<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected $fillable = ['product_id', 'country_id', 'email', 'ip', 'date'];
    
    public $timestamps = false;
    
    public function product() {
        return $this->belongsTo(Product::class);
    }
    
    public function country() {
        return $this->belongsTo(Country::class);
    }
}
