<?php

namespace JulioBitencourt\Cart\Storage\Session;

use JulioBitencourt\Cart\Storage\StorageInterface;
use Illuminate\Session\Store as Session;

/**
 * Stores the Cart data using Laravel's Session
 */
class SessionRepository implements StorageInterface
{
    /**
     * @var Cart
     */
    protected $session;

    /**
     * @param Cart $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * Get all cart items stored on the session
     * @return array
     */
    public function get()
    {
        return $this->session->get('cart');
    }

    /**
     * Insert the data in the session.
     *
     * @return array
     */
    public function insert($data)
    {
        $this->session->push('cart', $data);
    }

    /**
     * Update the item on the session
     * @param  integer $id
     * @param  integer $quantity
     * @return void
     */
    public function update($id, $quantity)
    {
        $storedData = $this->get();
        $storedData[$id]['quantity'] = $quantity;
        $this->session->put('cart', $storedData);
    }

    /**
     * Delete the item from the storage
     * @param  integer $id
     * @return void
     */
    public function delete($id)
    {
        $storedData = $this->get();
        unset($storedData[$id]);
        $this->session->put('cart', $storedData);
    }

    /**
     * Set the storage as an empty array
     * @return void
     */
    public function destroy()
    {
        $this->session->put('cart', []);
    }
}
