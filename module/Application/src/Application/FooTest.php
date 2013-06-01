<?php
namespace Application;
class FooTest extends \PHPUnit_Framework_TestCase
{
    function testBar()
    {
        $foo = new Foo;
        $this->assertEquals('hello',$foo->bar());
    }
}