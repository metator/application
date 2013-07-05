<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class AttributeController extends AbstractActionController
{
    function manageAction()
    {
//        $form = new Attribute_Form;
//        if($this->getRequest()->isPost()) {
//            if($form->isValid($this->getRequest()->getParams())) {
//                $this->view->values = print_r($this->getRequest()->getParams(),1);
//            }
//        }
//
//        $this->view->form = $form;

        return new ViewModel;
    }
}