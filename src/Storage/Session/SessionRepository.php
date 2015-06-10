<?php

namespace JulioBitencourt\Cart\Storage\Session;

use JulioBitencourt\Cart\Storage\StorageInterface;
use Illuminate\Session\Store as Session;

/**
 *
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

    public function update($id, $quantity)
    {
        $storedData = $this->get();
        $storedData[$id]['quantity'] = $quantity;
        $this->session->put('cart', $storedData);
    }

    public function delete($id)
    {
        $storedData = $this->get();
        unset($storedData[$id]);
        $this->session->put('cart', $storedData);
    }

    public function destroy()
    {
        $this->session->put('cart', []);
    }
}
