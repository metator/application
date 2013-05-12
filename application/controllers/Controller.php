<?php
abstract class Controller extends Zend_Controller_Action
{

    function init()
    {
        $this->view->headLink()->appendStylesheet('/bootstrap/css/bootstrap.css');
        $this->view->headScript()->appendFile('/js/jquery.js');
        $this->view->headScript()->appendFile('/bootstrap/js/bootstrap.js');
    }
}