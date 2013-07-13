<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Cart\Controller;

use Application\AbstractActionController;
use Zend\Session\Container;
use Metator\Cart\Cart;

class CartController extends AbstractActionController
{
    protected $productMapper;

    function indexAction()
    {
        $cart = $this->cart();

        // if they posted the form via a button
        if($this->getRequest()->isPost()) {

            if($this->params()->fromPost('checkout')) {
                return $this->redirect()->toRoute('checkout');
            }

            // loop over each post param
            foreach($this->params()->fromPost() as $key=>$value) {
                // if this param is a quantity box
                if(preg_match('/^quantity\-([0-9]+)$/', $key, $matches)) {
                    // update the quantity
                    $item_id = $matches[1];
                    $cart->setQuantity($item_id, $value);
                }
            }
        }

        return array(
            'cart'=>$cart
        );
    }

    function addAction()
    {
        $id = $this->params('id');
        $price = $this->productMapper()->load($id)->getBasePrice();

        $this->cart()->add($id, $price);
        $this->redirect()->toRoute('cart');
    }

}