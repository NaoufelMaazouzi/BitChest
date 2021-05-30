<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    public function sizes(){
        //Une taille appartient à plusieurs produits
        return $this->belongsToMany(Product::class);
    }
}
