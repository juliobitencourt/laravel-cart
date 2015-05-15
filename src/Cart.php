<?php namespace JulioBitencourt\Cart;

use JulioBitencourt\Cart\CartInterface;
use JulioBitencourt\Cart\Storage\StorageInterface as Storage;

class Cart implements CartInterface {

    protected $storage;

    protected static $cart = [];

    /**
     * Create a new Cart instance.
     *
     * @return void
     */
    public function __construct(Storage $storage)
    {
        $this->storage = $storage;

        $cart = $this->storage->get();
        if ($cart) static::$cart = $cart;
    }

    /**
     * Check if the item exists in the cart
     * If it exists, increments the item
     * Otherwise. Creates a new one.
     *
     * @param  array  $data
     * @return array
     */
    public function insert($data = [])
    {
        // First we check if the data is an array of items
        // If not, we create an array to iterate in.
        if (isset($data['sku']))
        {
            $data = array($data);
        }
        
        $items = $this->processItems($data);

        if (count($items) == 0) return false;

        return count($items) == 1 ? $items[0] : $items;
    }

    /**
     * Add a Child to an item.
     *
     * @return array
     */
    public function insertChild($parentId, $data = [])
    {
        $itemKey = $this->findBy('id', $parentId);

        if ($itemKey === false) return false;

        $parentDescription = static::$cart[$itemKey]['description'];
        
        $data = array_merge(
            ['parent_id' => $parentId, 'parent_description' => $parentDescription],
            $data
        );

        return $this->insert($data);
    }

    /**
     * Update an item.
     *
     * @param  int  $id
     * @param  int  $quantity
     * @return bool
     */
    public function update($id, $quantity)
    {
        $this->validate(['quantity' => $quantity]);

        $itemKey = $this->findBy('id', $id);

        if ($itemKey === false) return false;
        
        $this->updateOrDelete($itemKey, $quantity);
        return true;
    }

    /**
     * Remove an item.
     *
     * @param  int  $id
     * @return void
     */
    public function delete($id)
    {
        $itemKey = $this->findBy('id', $id);

        if ($itemKey === false) return false;
        
        unset(static::$cart[$itemKey]);

        $this->deleteChild($id);

        $this->storage->delete($itemKey);
    }

    /**
     * Destroy the cart.
     *
     * @return void
     */
    public function destroy()
    {
        static::$cart = [];
        $this->storage->destroy();
    }

    /**
     * Check if the cart is empty.
     *
     * @return bool
     */
    public function isEmpty()
    {
        return count(static::$cart) == 0;
    }

    /**
     * Check a list with the cart items.
     *
     * @return void
     */
    public function all()
    {
        return static::$cart;
    }

    /**
     * Sum the price of the items in the cart.
     *
     * @return float
     */
    public function total()
    {
        $amount = 0;

        foreach (static::$cart as $item)
        {
            $amount += $item['price'] * $item['quantity'];
        }

        return $amount;
    }

    /**
     * Sum the total items in the cart.
     *
     * @return integer
     */
    public function totalItems()
    {
        $count = 0;

        foreach (static::$cart as $item)
        {
            $count += $item['quantity'];
        }

        return $count;
    }

    /**
     * Check if an item exists in the collection.
     * If so, returns the key.
     *
     * @return bool
     */
    protected function findBy($field, $value)
    {
        foreach (static::$cart as $key => $item)
        {
            if (array_key_exists($field, $item) && $item[$field] == $value)
                return $key;
        }

        return false;
    }

    /**
     * Process the list of items to insert in the cart.
     *
     * @return array
     */
    protected function processItems($data)
    {
        $items = [];

        foreach ($data as $item)
        {
            $this->validate($item);

            $itemKey = $this->findBy('sku', $item['sku']);

            if ($itemKey === false)
            {
                $items[] = $this->createItem($item);
            }
            else
            {
                $items[] = $this->incrementItem($itemKey, $item['quantity']);
            }
        }

        return $items;
    }

    /**
     * If the item already exists in the cart, updates the quantity.
     *
     * @return void
     */
    protected function incrementItem($itemKey, $quantity)
    {
        $quantity = static::$cart[$itemKey]['quantity'] + $quantity;
        $this->updateOrDelete($itemKey, $quantity);
    }

    /**
     * Creates a new item.
     *
     * @return array
     */
    protected function createItem($data = [])
    {
        $data = array_merge(
            ['id' => $this->hash($data['sku'])],
            $data
        );
        static::$cart[] = $data;
        $this->storage->insert($data);
        return $data;
    }

    /**
     * Update the item quantity or remove the item
     * if the quantity is zero.
     *
     * @return void
     */
    protected function updateOrDelete($itemKey, $quantity)
    {
        if ($quantity == 0)
        {
            unset(static::$cart[$itemKey]);
            $this->storage->delete($itemKey);
        }
        else
        {
            static::$cart[$itemKey]['quantity'] = $quantity;
            $this->storage->update($itemKey, $quantity);
        }
    }

    protected function deleteChild($parentId)
    {
        $itemKey = $this->findBy('parent_id', $parentId);

        if ($itemKey === false) return false;

        $childId = static::$cart[$itemKey]['id'];

        $this->delete($childId);
    }

    /**
     * Creates a hash.
     *
     * @return string
     */
    protected function hash($value)
    {
        return md5($value);
    }

    /**
     * Validate the item
     *
     * @param  array  $fields
     * @return void
     *
     * @throws \InvalidArgumentException
     */
    protected function validate($fields)
    {
        foreach ($fields as $field => $value)
        {
            if ($field == 'parent_id' || $field == 'parent_description') continue;

            switch ($field) {
                case 'sku':
                    if (empty($value))
                    {
                        throw new \InvalidArgumentException('Invalid SKU for the item');
                    }
                    break;
                case 'description':
                    if (empty($value))
                    {
                        throw new \InvalidArgumentException('Invalid Description for the item');
                    }
                    break;
                case 'quantity':
                    if ( ! preg_match('/^-?(?:\d+|\d*\.\d+)$/', $value))
                    {
                        throw new \InvalidArgumentException('Invalid Quantity for the item');
                    }
                    break;
                case 'price':
                    if ( ! preg_match('/^-?(?:\d+|\d*\.\d+)$/', $value))
                    {
                        throw new \InvalidArgumentException('Invalid Price for the item');
                    }
                    break;

                case 'options':
                    if ( ! is_array($value))
                    {
                        throw new \InvalidArgumentException('Invalid Options for the item');
                    }
                    break;
                
                default:
                    throw new \InvalidArgumentException();
                    break;
            }
        }
    }
}
