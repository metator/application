<?php
namespace Image\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class ImageController extends AbstractActionController
{
    function viewAction()
    {
        $file = './data/images/'.$this->params('hash');
        return $this->getResponse()->setContent(file_get_contents($file));
    }
}