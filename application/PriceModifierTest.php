<?php
class PriceModifierTest extends PHPUnit_Framework_TestCase
{
    function testShouldNotAffectPrice()
    {
        $price_modifier = new PriceModifier;
        $new = $price_modifier->modify(5.15);
        $this->assertEquals(5.15, $new, 'should not modify price');
    }

    function testShouldAddFlatFee()
    {
        $price_modifier = new PriceModifier;
        $price_modifier->flatFee(5.15);
        $new = $price_modifier->modify(5.15);
        $this->assertEquals(10.30, $new, 'should add flat fee to price');
    }

    function testShouldAddPercentage()
    {
        $price_modifier = new PriceModifier;
        $price_modifier->percentage(10);
        $new = $price_modifier->modify(10);
        $this->assertEquals(11, $new, 'should add percentage to price');
    }
}