<?php
class IndexController extends Zend_Controller_Action
{

    function indexAction()
    {
        $this->view->headLink()->appendStylesheet('/bootstrap/css/bootstrap.css');
        $this->view->headScript()->appendFile('/js/jquery.js');
        $this->view->headScript()->appendFile('/bootstrap/js/bootstrap.js');

        $this->render('index','splash');
        $this->render('product/list','default',true);
        $this->render('categories','navigation',true);
    }
}