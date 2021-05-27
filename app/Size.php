<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    public function sizes(){
        return $this->belongsToMany(Product::class);
    }
}
