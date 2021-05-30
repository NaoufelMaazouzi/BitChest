<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Les fillable qui peuvent être modifiés/ajoutés
    protected $fillable = [ 'name' ];

    public function products(){

        // Une catégorie peut appartenir à plusieurs produits
        return $this->hasMany(Product::class);
    }
    
}
