<?php
abstract class AdminController extends Controller
{

    function init()
    {
        parent::init();
        $this->view->controller = $this->getRequest()->getParam('controller');
        $this->render('admin-navigation','navigation',true);
    }
}