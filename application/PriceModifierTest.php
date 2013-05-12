<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
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

    function testShouldAddFlatFeeThroughConstructor()
    {
        $price_modifier = new PriceModifier(array(
            'flat_fee'=>5.15
        ));
        $new = $price_modifier->modify(5.15);
        $this->assertEquals(10.30, $new, 'should add flat fee to price');
    }

    function testShouldGetFlatFee()
    {
        $price_modifier = new PriceModifier(array(
            'flat_fee'=>5.15
        ));
        $this->assertEquals(5.15,$price_modifier->flatFee(),'should get flat fee');
    }

    function testShouldAddPercentage()
    {
        $price_modifier = new PriceModifier;
        $price_modifier->percentage(10);
        $new = $price_modifier->modify(10);
        $this->assertEquals(11, $new, 'should add percentage to price');
    }

    function testShouldGetPercentage()
    {
        $price_modifier = new PriceModifier(array(
            'percentage'=>5.15
        ));
        $this->assertEquals(5.15,$price_modifier->percentage(),'should get percentage');
    }

    function testShouldAddPercentageThroughConstructor()
    {
        $price_modifier = new PriceModifier(array(
            'percentage'=>10
        ));
        $new = $price_modifier->modify(10);
        $this->assertEquals(11, $new, 'should add percentage to price');
    }
}