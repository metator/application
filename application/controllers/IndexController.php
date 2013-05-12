<?php
class IndexController extends Controller
{

    function indexAction()
    {
        $this->render('index','splash');
        $this->render('product/list','default',true);
        $this->render('categories','navigation',true);
    }
}