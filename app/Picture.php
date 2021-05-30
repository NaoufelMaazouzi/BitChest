<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Picture extends Model
{
    // Les fillable qui peuvent être modifiés/ajoutés
    protected $fillable = [
        'link', 'title'
    ];
    public function products(){
        // Une image appartient à un produit
        return $this->belongsTo(Product::class);
    }
}
