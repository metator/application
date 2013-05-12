<?php
class AttributeController extends AdminController
{

    function manageAction()
    {
        $form = new Attribute_Form;
        if($this->getRequest()->isPost()) {
            if($form->isValid($this->getRequest()->getParams())) {
                $this->view->values = print_r($this->getRequest()->getParams(),1);
            }
        }

        $this->view->form = $form;

        $this->render('manage','default',false);
    }
}