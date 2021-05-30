<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    // Les fillable qui peuvent être modifiés/ajoutés
    protected $fillable = [ 'name', 'description', 'state', 'price', 'status', 'reference', 'category_id' ];

    // ici le setter va récupérer la valeur à insérer en base de données
    // nous pourrons alors vérifier sa valeur avant que le modèle n'insère la donnée en base de données
    public function setCategoryIdAttribute($value){
       
        if($value == 0){
            $this->attributes['category_id'] = null;
        }else{
            $this->attributes['category_id'] = $value;
        }

    }

    public function picture(){
        // Un produit a une seule image
        return $this->hasOne(Picture::class);
    }

    public function category(){
        // Un produit à une seule catégorie
        return $this->belongsTo(Category::class);
    }

    public function sizes(){
        // Un produit à plusieurs tailles
        return $this->belongsToMany(Size::class);
    }

    public function scopePublished($query){
        // Retourne les produits publiés
        return $query->where('status', 'published');
    }

    public function scopeSolde($query){
        // Retourne les produits en solde
        return $query->where('state', 'solde');
    }
}
