<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
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