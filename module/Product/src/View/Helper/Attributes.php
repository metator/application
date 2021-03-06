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

        $criteria = array();
        foreach($attributes as $attribute) {
            if(isset($_GET[$attribute])) {
                $criteria[$attribute] = $_GET[$attribute];
            }
        }

        $values = array();
        foreach($attributes as $attribute) {
            $attributeValues = $dataMapper->listValues($attribute, $criteria);

            if(count($attributeValues)) {
                $values[$attribute] = $attributeValues;
            }
        }

        $sidebar = new ViewModel(array(
            'attributes'=> array_keys($values),
            'values' => $values,
            'hasSelection' => count($criteria)
        ));
        $sidebar->setTemplate('product/product/attributes');

        $htmlOutput = $sm->get('viewrenderer')
            ->render($sidebar);
        return $htmlOutput;
    }
}