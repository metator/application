<?php
class CategoryController extends AdminController
{
    function manageAction()
    {
        $this->render('manage','default',false);
    }
}