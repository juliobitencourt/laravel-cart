<?php namespace JulioBitencourt\Cart\Eloquent;

use JulioBitencourt\Cart\Storage\StorageInterface;
use JulioBitencourt\Cart\Storage\Eloquent\Entities\Cart;

/**
* 
*/
class EloquentRepository implements StorageInterface {

    /**
     * @var Cart
     */
    protected $model;
    
    /**
     * @param Cart $model
     */
    function __construct(Cart $model)
    {
        $this->model = $model;
    }

    public function findBy($field, $value)
    {

    }

}