<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
namespace Metator\Product\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\View\Model\ViewModel;
use Zend\ServiceManager\ServiceLocatorInterface;

class Attributes extends AbstractHelper implements ServiceLocatorAwareInterface
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
        $dataMapper = $sm->get('Product\Attribute\DataMapper');

        $attributes = $dataMapper->listAttributes();
        $values = array();
        foreach($attributes as $attribute) {
            $values[$attribute] = $dataMapper->listValues($attribute);
        }

        $sidebar = new ViewModel(array(
            'attributes'=> $attributes,
            'values' => $values
        ));
        $sidebar->setTemplate('product/product/attributes');

        $htmlOutput = $sm->get('viewrenderer')
            ->render($sidebar);
        return $htmlOutput;
    }
}