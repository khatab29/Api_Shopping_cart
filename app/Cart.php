<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cart extends Model
{
    use SoftDeletes;

    /**
    * Defining many to many realation with Product model
    *
    *
    */
    public function products()
    {
        return $this->belongsToMany('App\Product');
    }
}
