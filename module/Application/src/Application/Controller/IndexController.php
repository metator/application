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
        $comments->setTemplate('application/index/another-child');
        $result->addChild($comments, 'another_child');


        return $result;
    }
}
