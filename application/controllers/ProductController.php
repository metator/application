<?php
class ProductController extends AdminController
{
    function manageAction()
    {
        $this->render('manage','default',false);
    }

    function editAction()
    {
        $this->view->form = new Product_Form;

        $this->view->headScript()->appendFile('/js/jquery.metadata.pack.js');
        $this->view->headScript()->appendFile('/js/product-form.js');

        $this->render('product/edit','default',true);
    }
}