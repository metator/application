<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Cart\Controller;

use Application\AbstractActionController;
use Metator\Cart\CheckoutForm;
use Omnipay\Common\GatewayFactory;

class CheckoutController extends AbstractActionController
{
    protected $productMapper, $orderMapper;

    function indexAction()
    {
        $cart = $this->cart();
        $form = new CheckoutForm;

        if($this->getRequest()->isPost() && $form->isValid($this->params()->fromPost())) {

            $response = $this->capturePayment();
            if ($response->isSuccessful()) {
                $reference = $response->getTransactionReference();
                $id = $this->saveOrder($reference, $form);
                return $this->redirect()->toRoute('checkout_confirmation',array(
                    'id'=>$id
                ));
            }

            if ($response->isRedirect()) {
                // redirect to offsite payment gateway
                return $response->redirect();
            }

            // payment failed: display message to customer
            echo $response->getMessage();
        }

        return array(
            'cart'=>$cart,
            'form'=>$form
        );
    }

    function confirmationAction()
    {
        return array(
            'id'=>$this->params('id')
        );
    }

    function amount()
    {
        return $this->cart()->totalPrice();
    }

    function capturePayment()
    {
        $gateway = GatewayFactory::create('AuthorizeNet_AIM');
        $gateway->setApiLoginId('2Scf4XP24');
        $gateway->setTransactionKey('96eRW9zn78X5s5dN');

        $card = array(
            'number' => '4242424242424242',
            'expiryMonth' => '6',
            'expiryYear' => '2016',
            'cvv' => '123'
        );
        $response = $gateway->purchase(array(
            'developerMode'=>true,
            'amount' => $this->amount(),
            'currency' => 'USD',
            'card' => $card
        ))->send();
        return $response;
    }

    function saveOrder($reference, $form)
    {
        $order = array(
            'shipping'=>$form->getValues()['shipping'],
            'billing'=>$form->getValues()['billing'],
            'items'=> $this->cart(),
            'api_reference'=>$reference,
            'amount'=>$this->amount()
        );
        $orderMapper = $this->orderMapper();
        $id = $orderMapper->save($order);
        return $id;
    }
}