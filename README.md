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
        "juliobitencourt/laravel-cart": "dev-master"
    }
}
```

Add the service provider to your app/config/app.php in the service providers array

```php
'JulioBitencourt\Cart\CartServiceProvider',
```

## Usage

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
