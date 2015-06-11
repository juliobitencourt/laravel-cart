<?php

namespace JulioBitencourt\Cart\Storage;

interface StorageInterface
{
    public function get();

    public function insert($data);

    public function update($itemKey, $quantity);

    public function delete($itemKey);

    public function destroy();

    public function setEmail($email);
}
