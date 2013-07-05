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
    protected $productMapper, $categoryMapper;

    public function indexAction()
    {
        $this->layout('layout/layout-2col-left.phtml');

        $layoutViewModel = $this->layout();

        // add categories to sidebar
        $sidebar = new ViewModel(array(
            'categories'=>$this->categoryMapper()->findStructuredAll()
        ));
        $sidebar->setTemplate('layout/categories');
        $layoutViewModel->addChild($sidebar, 'navigation');

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
        //return $this->productMapper()->load(5);
    }

    function productMapper()
    {
        if (!$this->productMapper) {
            $sm = $this->getServiceLocator();
            $this->productMapper = $sm->get('Application\Product\DataMapper');
        }
        return $this->productMapper;
    }

    function categoryMapper()
    {
        if (!$this->categoryMapper) {
            $sm = $this->getServiceLocator();
            $this->categoryMapper = $sm->get('Application\Category\DataMapper');
        }
        return $this->categoryMapper;
    }
}
