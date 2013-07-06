<?php
namespace Image\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ImageController extends AbstractActionController
{
    const MAX = 999;

    function viewAction()
    {
        $file = './data/images/'.$this->params('hash');
        return $this->getResponse()->setContent(file_get_contents($file));
    }

    function resizedAction()
    {
        $hash = $this->params('hash');
        $file = './data/images/'.$hash;

        $size = $this->params('size');
        preg_match('/([0-9]+)x([0-9]+)/', $size, $matches);

        $width   = $matches[1];
        $height  = $matches[2];

        if($width>self::MAX || $height>self::MAX) {
            throw new Exception("that's way too big for a thumbnail, my feeble server wont handle it! Aborting.");
        }

        $newFile = './data/images/'.$size.'/'.$hash;
        $outputDir = dirname($newFile);
        if(!file_exists($outputDir)) {
            mkdir($outputDir);
        }

        $size    = new \Imagine\Image\Box($width, $height);
        $mode    = \Imagine\Image\ImageInterface::THUMBNAIL_INSET;

        $tmpFile = sys_get_temp_dir().'/'.md5(uniqid()).'.jpg';

        $imagine = new \Imagine\Gd\Imagine;
        $imagine->open($file)
            ->thumbnail($size, $mode)
            ->save($tmpFile,array(
                'quality'=>100
            ));

        rename($tmpFile, $newFile);

        return $this->getResponse()->setContent(file_get_contents($newFile));
    }
}