<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
    protected $fillable = [
    'title', 'price', 'discount', 'final_price',
    ];

    /**
    * Defining many to many realation with User model
    *
    *
    */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }
}
