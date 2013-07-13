<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Metator\Image;
class Saver
{
    protected $image_data;

    function __construct($imageData)
    {
        $this->image_data = $imageData;
    }

    function getHash()
    {
        return sha1($this->image_data);
    }

    function save()
    {
        file_put_contents('./data/images/'.$this->getHash(), $this->image_data);
    }
}