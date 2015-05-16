<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartItemsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cart_items', function(Blueprint $table)
		{
			$table->char('id', 32);
			$table->integer('cart_id')->unsigned();
			$table->string('sku');
			$table->string('description');
			$table->integer('quantity')->unsigned();
			$table->decimal('price', 5, 2);
			$table->string('options');
			$table->timestamps();
			$table->foreign('cart_id')->references('id')->on('carts');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cart_items');
	}

}
