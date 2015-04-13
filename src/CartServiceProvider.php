<?php namespace JulioBitencourt\Cart;

use Illuminate\Support\ServiceProvider;

class CartServiceProvider extends ServiceProvider {

    public function register()
    {
        $this->app->bind(
            'JulioBitencourt\Cart\CartInterface',
            'JulioBitencourt\Cart\Session\Cart'
        );
    }
}