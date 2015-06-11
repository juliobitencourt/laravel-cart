<?php

namespace JulioBitencourt\Cart\Storage\Eloquent\Entities;

use Illuminate\Database\Eloquent\Model;

class Items extends Model
{
    /**
     * Table name.
     *
     * @var string
     */
    protected $table = 'cart_items';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['id', 'cart_id', 'sku', 'description', 'quantity', 'price', 'options'];

    /**
     * Get the cart that owns the item.
     */
    public function cart()
    {
        return $this->belongsTo('JulioBitencourt\Cart\Storage\Eloquent\Entities\Cart');
    }
}
