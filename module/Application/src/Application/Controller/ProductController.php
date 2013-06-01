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
use Application\Product\Form;

class ProductController extends AbstractActionController
{

    function manageAction()
    {
        //$this->render('manage','default',false);
        return new ViewModel;
    }

    function editAction()
    {
//        $this->view->headScript()->appendFile('/js/jquery.metadata.pack.js');
//        $this->view->headScript()->appendFile('/js/product-form.js');

        $form = new Form;
        $form->setView(new \Zend_View);
        return new ViewModel(array(
            'form'=>$form
        ));
    }
}