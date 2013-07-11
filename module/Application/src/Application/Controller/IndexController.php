<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Application\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    protected $productMapper;

    public function indexAction()
    {
        if( $this->params()->fromQuery('page') > 100 ) {
            throw new \Exception('You cant go past 100 pages for performance reasons');
        }

        $page = $this->params()->fromQuery('page',1);
        $perpage = 6;
        $offset = ($page * $perpage)-$perpage;

        $products = $this->productMapper()->find(array(), $offset, $perpage);
        $productCount = $this->productMapper()->count();

        $pageAdapter = new \Zend\Paginator\Adapter\Null($productCount);
        $paginator = new \Zend\Paginator\Paginator($pageAdapter);
        $paginator->setCurrentPageNumber($page);

        return array(
            'start'=>$offset+1,
            'end'=>$offset+$perpage,
            'total'=>$productCount,
            'paginator'=>$paginator,
            'products'=>$products,
        );
    }

    function products()
    {
        return $this->productMapper()->find();
    }

}
