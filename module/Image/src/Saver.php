<?php
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