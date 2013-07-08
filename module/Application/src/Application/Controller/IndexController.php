<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $productMapper;

    public function indexAction()
    {
        // render splash
        $result = new ViewModel();
        $result->setTemplate('application/index/index');

        // render some featured products
        $products = new ViewModel();
        $products->setTemplate('product/product/list');
        $products->setVariable('products',$this->products());
        $result->addChild($products, 'product_list');

        return $result;
    }

    function products()
    {
        return $this->productMapper()->find();
    }

    /** @return \Metator\Product\DataMapper */
    function productMapper()
    {
        if (!$this->productMapper) {
            $sm = $this->getServiceLocator();
            $this->productMapper = $sm->get('Product\DataMapper');
        }
        return $this->productMapper;
    }


}
