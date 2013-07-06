<?php
namespace Metator\Image;
class SaverTest extends \PHPUnit_Framework_TestCase
{
    function tearDown()
    {
        $file = './data/images/427d0d1bb2c597b5faceb50786c0d6cfb9bdee0d';
        if(file_exists($file)) {
            unlink($file);
        }
    }

    function testShouldHashImage()
    {
        $imageData = '<pretend this is image data>';
        $saver = new Saver($imageData);
        $this->assertEquals('427d0d1bb2c597b5faceb50786c0d6cfb9bdee0d', $saver->getHash());
    }

    function testShouldSaveAsHash()
    {
        $imageData = '<pretend this is image data>';
        $saver = new Saver($imageData);
        $saver->save();
        $this->assertTrue(file_exists('./data/images/427d0d1bb2c597b5faceb50786c0d6cfb9bdee0d'));
    }

}