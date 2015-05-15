<?php namespace JulioBitencourt\Cart;

use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind(
            'JulioBitencourt\Cart\StorageInterface',
            'JulioBitencourt\Cart\Storage\Session\SessionStorage'
        );
    }
}