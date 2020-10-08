<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Storage;

class Product extends Model
{
    //
    public function getImageUrlAttribute()
    {
        if(Str::startswith($this->attributes['image'], ['http://','https://'])){
           return $this->attributes['image'];
        }
        
        return Storage::disk('public')->url($this->attributes['image']);
    }

public function cart()
{
    return $this->hasMany('App\Cart');
}

public function items()
{
    return $this->hasMany('App\OrderItem');
}

}
