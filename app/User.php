<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    // Les fillable qui peuvent être modifiés/ajoutés
    protected $fillable = [
        'name', 'email', 'password','role', 'solde'
    ];

    //Fonction pour savoir si l'utilisateur est admin
    public function isAdmin() {
        return $this->role === 'admin';
     }
 
    //Fonction pour savoir si l'utilisateur est un simple utilisateur
     public function isUser() {
        return $this->role === 'user';
     }

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    // Les attributs 'cachés'
    protected $hidden = [
        'password', 'remember_token',
    ];
}
