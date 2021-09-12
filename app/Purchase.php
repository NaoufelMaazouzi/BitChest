<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    // Les fillable qui peuvent être modifiés/ajoutés
    protected $fillable = [
        'user_id', 'crypto', 'price', 'amount','totalValue', 'imageUrl', 'date'
    ];

    // Clé primaire
    protected $primaryKey = 'user_id';
}