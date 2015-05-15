<?php namespace JulioBitencourt\Cart\Storage;

interface StorageInterface {

    public function get();

    public function insert($data);

    public function update($id, $quantity);

    public function delete($id);

    public function destroy();

}