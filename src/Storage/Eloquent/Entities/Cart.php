<?php

namespace JulioBitencourt\Cart\Storage\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;
use JulioBitencourt\Cart\Storage\Eloquent\Entities\Items;

class Cart extends Model
{

	/**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'token'];

	/**
     * Get the items for the cart.
     */
    public function items()
    {
        return $this->hasMany(Items::class);
    }
}
