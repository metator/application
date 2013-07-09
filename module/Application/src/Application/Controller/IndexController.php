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
        $paginator = $this->productMapper()->findPaginated();
        $paginator->setCurrentPageNumber((int)$this->params()->fromQuery('page', 1));
        $paginator->setItemCountPerPage(9);

        return array(
            'paginator'=>$paginator
        );
    }

    function products()
    {
        return $this->productMapper()->find();
    }

}
