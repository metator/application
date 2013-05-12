<?php
abstract class AdminController extends Controller
{

    function postDispatch()
    {
        $this->view->controller = $this->getRequest()->getParam('controller');
        $this->render('admin-navigation','navigation',true);
    }
}