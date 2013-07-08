<?php
namespace Category\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorInterface;

class CategoryStructure extends AbstractHelper implements ServiceLocatorAwareInterface
{
    /**
     * Set the service locator.
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return CustomHelper
     */
    public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
    {
        $this->serviceLocator = $serviceLocator;
        return $this;
    }
    /**
     * Get the service locator.
     *
     * @return \Zend\ServiceManager\ServiceLocatorInterface
     */
    public function getServiceLocator()
    {
        return $this->serviceLocator;
    }

    function __invoke()
    {
        $sm = $this->getServiceLocator()->getServiceLocator();
        $categoryMapper = $sm->get('Category\DataMapper');

        $sidebar = new ViewModel(array(
            'categories'=> $categoryMapper->findStructuredAll()
        ));
        $sidebar->setTemplate('layout/categories');

        $htmlOutput = $sm->get('viewrenderer')
            ->render($sidebar);
        return $htmlOutput;
    }
}