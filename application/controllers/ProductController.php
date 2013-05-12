<?php
class ProductController extends Zend_Controller_Action
{

    function editAction()
    {
        $this->view->form = new Product_Form;
        $this->view->headLink()->appendStylesheet('/bootstrap/css/bootstrap.css');
        $this->view->headScript()->appendFile('/js/jquery.js');
        $this->view->headScript()->appendFile('/js/jquery.metadata.pack.js');
        $this->view->headScript()->appendFile('/bootstrap/js/bootstrap.js');
        $this->view->headScript()->appendFile('/js/product-form.js');
        $this->render('admin-navigation','navigation',true);
        $this->render('product/edit','default',true);
    }
}