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
        // Use an alternative layout
        $layoutViewModel = $this->layout();

        // add an additional layout to the root view model (layout)
        $sidebar = new ViewModel();
        $sidebar->setTemplate('layout/categories');
        $layoutViewModel->addChild($sidebar, 'navigation');

        // set up action view model and associated child view models
        $result = new ViewModel();
        $result->setTemplate('application/index/index');

        $comments = new ViewModel();
        $comments->setTemplate('application/product/list');
        $comments->setVariable('products',$this->products());
        $result->addChild($comments, 'product_list');

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
            $this->productMapper = $sm->get('Application\ProductMapper');
        }
        return $this->productMapper;
    }
}
