<?php
/**
 * Metator (http://metator.com/)
 * @copyright  Copyright (c) 2013 Vehicle Fits, llc
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Application\Controller;

use Zend\Console\Request as ConsoleRequest;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Console\ColorInterface;
use Metator\Product\Importer;

class ConsoleController extends AbstractActionController
{
    function indexattributesAction()
    {
        /**
         * Enforce valid console request
         */
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        $start = microtime(true);

        $db = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $attributeDataMapper = new \Metator\Product\Attribute\DataMapper($db);
        $attributeDataMapper->index();

        $end = microtime(true);

        $console = $this->getServiceLocator()->get('console');
        $console->write('All Done. Took '.sprintf('%.4fs', $end - $start).'', ColorInterface::YELLOW);
        echo "\n";
    }

    function sampleproductsAction()
    {
        /**
         * Enforce valid console request
         */
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        $products = preg_replace('/[^0-9]/','',$this->params('number'));
        $categories = preg_replace('/[^0-9]/','',$this->params('categories', 0));

        $start = microtime(true);
        $this->create($products, $categories);
        $end = microtime(true);

        $console = $this->getServiceLocator()->get('console');
        $console->write('Created '.number_format($products) .' sample products', ColorInterface::LIGHT_CYAN);
        echo "\n";
        $console->write('All Done. Took '.sprintf('%.4fs', $end - $start).'', ColorInterface::YELLOW);
        echo "\n";
    }

    function create($products, $categories)
    {
        if(!$categories) {
            $csv = "sku,active,name,attributes\n";
            for($i=0; $i<$products; $i++) {
                $json = array(
                    'size'=>rand(1,3),
                    'color'=>rand(1,10),
                );
                $json = \Zend\Json\Json::encode($json);
                $json = str_replace('"', '\\"', $json);
                $csv.= "sku-$i,1,name-$i,\"$json\"\n";
            }
        } else {
            $csv = "sku,active,name,categories\n";
            for($i=0; $i<$products; $i++) {
                $j = rand(1,$categories);
                $csv.= "sku-$i,1,name-$i,category-$j\n";
            }
        }

        $db = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $importer = new Importer($db);
        $importer->importFromText($csv);
    }
}