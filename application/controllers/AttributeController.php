<?php
class AttributeController extends AdminController
{

    function manageAction()
    {
        $form = new Attribute_Form;
        if($this->getRequest()->isPost() && $form->isValid($this->getRequest()->getParams())) {
            $this->view->values = print_r($this->getRequest()->getParams(),1);
        }
        $this->view->form = $form;

        $this->view->headScript()->appendFile('/js/attribute-form.js');
        $this->render('manage','default',false);
    }

    /**
     * Ajax action that returns the dynamic form field
     */
    public function newfieldAction()
    {
        $id = $this->_getParam('id', null);

        $element = new Zend_Form_Element_Text($id, array(
            'belongsTo'=>'value'
        ));
        $element->setRequired(true)->setLabel('Value');

        echo $element->__toString();
        exit;
    }
}