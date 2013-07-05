<?php
namespace Metator\Cart;

class CartTest extends \PHPUnit_Framework_TestCase
{
    function testShouldAddID()
    {
        $cart = new Cart;
        $cart->add(5);
        $this->assertEquals(array(5), $cart->items(), 'should add ID');
    }

    function testShouldNotAddIDTwice()
    {
        $cart = new Cart;
        $cart->add(5);
        $cart->add(5);
        $this->assertEquals(array(5), $cart->items(), 'should not add ID twice');
    }

    function testShouldGetSingleQuantity()
    {
        $cart = new Cart;
        $cart->add(5);
        $this->assertEquals(1, $cart->quantity(5), 'should get single quantity');
    }

    function testShouldGetMultipleQuantity()
    {
        $cart = new Cart;
        $cart->add(5);
        $cart->add(5);
        $cart->add(5);
        $this->assertEquals(3, $cart->quantity(5), 'should get multiple quantity');
    }

    function testShouldRemoveFromItems()
    {
        $cart = new Cart;
        $cart->add(5);
        $cart->remove(5);
        $this->assertEquals(array(), $cart->items(), 'should remove from items');
    }

    function testShouldRemoveFromQuantity()
    {
        $cart = new Cart;
        $cart->add(5);
        $cart->remove(5);
        $this->assertEquals(0, $cart->quantity(5), 'should remove from quantity');
    }

    function testShouldSetQuantity()
    {
        $cart = new Cart;
        $cart->add(5);
        $cart->setQuantity(5,500);
        $this->assertEquals(500, $cart->quantity(5), 'should set quantity');
    }

    function testShouldRemoveWhenSetQuantity0()
    {
        $cart = new Cart;
        $cart->add(5);
        $cart->setQuantity(5,0);
        $this->assertEquals(array(), $cart->items(), 'should remove when set quantity to 0');
    }

}