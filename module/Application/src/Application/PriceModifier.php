<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * PriceModifier is a class to represent & calculate markups to prices. A flat fee or percentage markup is
 * specified, then you can modify a price which entails adding the specified fee to said price.
 */
namespace Application;
class PriceModifier
{
    protected $flat_fee;
    protected $percentage;

    function __construct($params=array())
    {
        $this->flat_fee = isset($params['flat_fee']) ? $params['flat_fee'] : null;
        $this->percentage = isset($params['percentage']) ? $params['percentage'] : null;
    }

    function modify($price)
    {
        if($this->flat_fee) {
            return $price + $this->flat_fee;
        }
        if($this->percentage) {
            return $price * (100+$this->percentage)/100;
        }
        return $price;
    }

    function flatFee($fee=null)
    {
        if(is_null($fee)) {
            return $this->flat_fee;
        }
        $this->flat_fee = $fee;
    }

    function percentage($percent=null)
    {
        if(is_null($percent)) {
            return $this->percentage;
        }
        $this->percentage = $percent;
    }
}