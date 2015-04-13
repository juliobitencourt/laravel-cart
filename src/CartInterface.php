<?php namespace JulioBitencourt\Cart;

interface CartInterface {

	public function insert($item);

	public function update($id, $quantity);

	public function delete($id);

	public function all();

	public function isEmpty();

	public function destroy();

	public function totalItems();

	public function total();

}