<?php
class PriceModifier
{
    protected $flat_fee;
    protected $percentage;

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

    function flatFee($fee)
    {
        $this->flat_fee = $fee;
    }

    function percentage($percent)
    {
        $this->percentage = $percent;
    }
}