<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [ 'name', 'description', 'price', 'size', 'status', 'reference', 'category' ];

    public function picture(){
        return $this->hasOne(Picture::class);
    }

    public function category(){
        return $this->belongsTo(Category::class);
    }

    public function scopePublished($query){
        return $query->where('status', 'published');
    }
}
