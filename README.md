[![Build Status](https://travis-ci.org/juliobitencourt/laravel-cart.svg?branch=master)](https://travis-ci.org/juliobitencourt/laravel-cart)
[![Latest Stable Version](https://poser.pugx.org/juliobitencourt/laravel-cart/v/stable)](https://packagist.org/packages/juliobitencourt/laravel-cart) [![Total Downloads](https://poser.pugx.org/juliobitencourt/laravel-cart/downloads)](https://packagist.org/packages/juliobitencourt/laravel-cart) [![Latest Unstable Version](https://poser.pugx.org/juliobitencourt/laravel-cart/v/unstable)](https://packagist.org/packages/juliobitencourt/laravel-cart) [![License](https://poser.pugx.org/juliobitencourt/laravel-cart/license)](https://packagist.org/packages/juliobitencourt/laravel-cart)

[![Code Climate](https://codeclimate.com/github/juliobitencourt/laravel-cart/badges/gpa.svg)](https://codeclimate.com/github/juliobitencourt/laravel-cart)

A lightweight PHP Shopping Cart for Laravel 5.
============

## Installation

### With Composer

```
$ composer require juliobitencourt/laravel-cart
```

```json
{
    "require": {
        "juliobitencourt/laravel-cart": "1.0.0"
    }
}
```

Add the service provider to your app/config/app.php in the service providers array

```php
'JulioBitencourt\Cart\CartServiceProvider',
```

Publish the resources

```shell
php artisan vendor:publish
```

Check the config/laravel-cart.php file. The storage-driver config can have the values Session (default) or Database.

In case you use Database as the storage driver you have to run the migrations

```shel
php artisan migrate
```

## Usage

### Inject the class into the constructor.

```php
use JulioBitencourt\Cart\Cart;

class CartController extends Controller {

	protected $cart;

	public function __construct(Cart $cart)
	{
		$this->cart = $cart;
	}
```

### Insert a new Item to the cart.

If you insert an item with the same SKU twice, the item quantity will be updated.

```php
$item = [
	'sku' => '123456',
	'description' => 'PlayStation 4',
	'price' => 300,
	'quantity' => 1
];

$result = $this->cart->insert($item);
```

### Insert a Child Item.

```php
$item = [
	'sku' => '111111',
	'description' => '2 Year Protection',
	'price' => 30.50,
	'quantity' => 1
];

$result = $this->cart->insertChild($parentId, $item);
```

### Update an item

If you update an item with 0 quantity, it will be removed from the cart's list of items

```php
$result = $this->cart->update($id, $quantity);
```

### Remove an item

```php
$this->cart->delete($id);
```

### Destroy the cart

```php
$this->cart->destroy();
```

### Checking if the cart is empty

```php
$this->cart->isEmpty(); // Returns true or false
```

### Returning an array with the list of items

```php
$this->cart->all();
```

### Count the total of items

```php
$this->cart->totalItems();
```

### Sum the cart total price

```php
$this->cart->total();
```

### Identify the cart with the e-mail.

This is useful if you want to implement abandoned cart recovery.

```php
$this->cart->setEmail($email)
```