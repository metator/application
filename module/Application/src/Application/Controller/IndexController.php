<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Application\Controller;

use Application\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public function indexAction()
    {
        if( $this->params()->fromQuery('page') > 100 ) {
            throw new \Exception('You cant go past 100 pages for performance reasons');
        }

        $page = $this->params()->fromQuery('page',1);
        $perpage = 6;
        $offset = ($page * $perpage)-$perpage;

        $attributes = array();
        $allowed_attributes = $this->attributeMapper()->listAttributes();
        foreach($allowed_attributes as $attribute) {
            if($this->params()->fromQuery($attribute)) {
                $attributes[$attribute] = $this->params()->fromQuery($attribute);
            }
        }

        $products = $this->productMapper()->find(['attributes'=>$attributes,'active'=>1], $offset, $perpage);
        $productCount = $this->productMapper()->count(['attributes'=>$attributes]);

        $pageAdapter = new \Zend\Paginator\Adapter\Null($productCount);
        $paginator = new \Zend\Paginator\Paginator($pageAdapter);
        $paginator->setItemCountPerPage($perpage);
        $paginator->setCurrentPageNumber($page);

        if($offset+$perpage > $productCount) {
            $end = $productCount;
        } else {
            $end = $offset+$perpage;
        }

        return array(
            'start'=>$offset+1,
            'end'=>$end,
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
