<?php namespace spec\JulioBitencourt\Cart\Session;

use PhpSpec\ObjectBehavior;
use PhpSpec\Matcher\InlineMatcher;
use Prophecy\Argument;
use Illuminate\Session\Store as Session;

class CartSpec extends ObjectBehavior
{
    function let(Session $session)
    {
    	$this->beConstructedWith($session);
    	$this->shouldHaveType('JulioBitencourt\Cart\Session\Cart');
    }

    function it_store_a_new_item()
    {
        $this->destroy();
    	$this->insert(['sku' => '123465', 'description' => 'XBox', 'quantity' => 1, 'price' => 1500.00, 'options' => ['color' => 'Red']]);
    	$this->totalItems()->shouldReturn(1);
    	$this->total()->shouldReturn(1500.00);
    }

    function it_store_a_array_of_items()
    {
        $this->destroy();
        $this->insert([
            ['sku' => '111111', 'description' => 'XBox', 'quantity' => 1, 'price' => 1000.00],
            ['sku' => '222222', 'description' => 'PlayStation', 'quantity' => 1, 'price' => 2000.00]
        ]);
        $this->totalItems()->shouldReturn(2);
        $this->total()->shouldReturn(3000.00);
    }

    function it_increment_an_item_added_twice()
    {
    	$this->destroy();
    	$this->insert(['sku' => '123456', 'description' => 'XBox', 'quantity' => 1, 'price' => 1500.00]);
    	$this->insert(['sku' => '123456', 'description' => 'XBox', 'quantity' => 10, 'price' => 1500.00]);
    	$this->all()->shouldHaveCount(1);
    	$this->totalItems()->shouldReturn(11);
    	$this->total()->shouldReturn(16500.00);	
    }

    function it_update_an_item()
    {
    	$this->destroy();
    	$item = $this->insert(['sku' => '123456', 'description' => 'XBox', 'quantity' => 5, 'price' => 1500.00]);
    	$this->update($item['id'], 10);
    	$this->totalItems()->shouldReturn(10);
    }

    function it_return_false_if_an_item_doesnt_exists_on_update()
    {
    	$this->destroy();
    	$this->update('123456', 10)->shouldReturn(false);
    }

    function it_remove_an_item()
    {
    	$this->destroy();
    	$item = $this->insert(['sku' => '123456', 'description' => 'XBox', 'quantity' => 10, 'price' => 1500.00]);
    	$this->delete($item['id']);
    	$this->totalItems()->shouldReturn(0);
    }

    function it_remove_an_item_if_the_quantity_is_zero()
    {
        $this->destroy();
        $item = $this->insert(['sku' => '123456', 'description' => 'XBox', 'quantity' => 10, 'price' => 1500.00]);
        $this->update($item['id'], 0);
        $this->isEmpty()->shouldReturn(true);
    }

    function it_add_an_child_to_an_item()
    {
        $this->destroy();
        $item = $this->insert(['sku' => '123456', 'description' => 'XBox', 'quantity' => 1, 'price' => 1500.00]);
        $childData = ['sku' => '123456-1', 'description' => 'Extended Warranty', 'quantity' => 1, 'price' => 450.00];
        $this->insertChild($item['id'], $childData);
        $this->totalItems()->shouldReturn(2);
        $this->total()->shouldReturn(1950.00); 
    }

    function it_remove_an_child_if_the_parent_is_removed()
    {
        $this->destroy();
        $item = $this->insert(['sku' => '123456', 'description' => 'XBox', 'quantity' => 1, 'price' => 1500.00]);
        $childData = ['sku' => '123456-1', 'description' => 'Extended Warranty', 'quantity' => 1, 'price' => 450.00];
        $this->insertChild($item['id'], $childData);
        $this->delete($item['id']);
        $this->totalItems()->shouldReturn(0);
        $this->total()->shouldReturn(0); 
    }

    function it_return_a_child_item_with_the_parent_description()
    {
        $this->destroy();
        $item = $this->insert(['sku' => '123456', 'description' => 'XBox', 'quantity' => 1, 'price' => 1500.00]);
        $childData = ['sku' => '123456-1', 'description' => 'Extended Warranty', 'quantity' => 1, 'price' => 450.00];
        $this->insertChild($item['id'], $childData);
        $this->all()[1]->shouldHaveKey('parent_description');
        $this->all()[1]->shouldContain('XBox');
    }

    function it_destroy_the_cart()
    {
    	$this->insert(['sku' => '123456', 'description' => 'XBox', 'quantity' => 5, 'price' => 1500.00]);
    	$this->destroy();
    	$this->totalItems()->shouldReturn(0);
    }

    function it_return_the_cart_count_and_total()
    {
    	$this->destroy();
    	$this->insert(['sku' => '123465', 'description' => 'XBox', 'quantity' => 1, 'price' => 1500.00]);
    	$this->insert(['sku' => '456456', 'description' => 'PlayStation 4', 'quantity' => 1, 'price' => 2000.00]);
    	$this->insert(['sku' => '789798', 'description' => 'Wii', 'quantity' => 1, 'price' => 1000.00]);
    	$this->totalItems()->shouldReturn(3);
    	$this->total()->shouldReturn(4500.00);
    }

    function it_return_a_list_of_items()
    {
    	$this->destroy();
    	$this->insert(['sku' => '123456', 'description' => 'XBox', 'quantity' => 1, 'price' => 1500.00]);
    	$this->all()[0]->shouldHaveKey('description');
    	$this->all()[0]->shouldContain('XBox');
    }

    function it_should_validate_a_new_item()
    {
        $this->destroy();
        $this->shouldThrow('InvalidArgumentException')->duringInsert(['sku' => '123456', 'description' => 'Xbox', 'quantity' => 'a', 'price' => '1111,01']);
    }

    function it_should_validate_an_item_with_options()
    {
        $this->destroy();
        $this->shouldThrow('InvalidArgumentException')->duringInsert(['sku' => '123456', 'description' => 'Xbox', 'quantity' => '1', 'price' => '1000.00', 'options' => 'foo']);
    }

    function it_should_validate_an_item_before_update()
    {
        $this->destroy();
        $item = $this->insert(['sku' => '123456', 'description' => 'XBox', 'quantity' => 5, 'price' => 1500.00]);
        $this->shouldThrow('InvalidArgumentException')->duringUpdate($item['id'], 'a');
    }
    
}