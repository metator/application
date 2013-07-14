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
use BjyProfiler\Db\Profiler\Profiler;

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

        // turn off the profiler because it'll eat up memory otherwise!
        if(is_object($db->getProfiler())) {
            $db->getProfiler()->disable();
        }

        $attributeDataMapper = new \Metator\Product\Attribute\DataMapper($db);
        $attributeDataMapper->index();

        $end = microtime(true);

        $console = $this->getServiceLocator()->get('console');
        $console->write('All Done. Took '.sprintf('%.4fs', $end - $start).'', ColorInterface::YELLOW);
        echo "\n";
        $console->write('Memory: '.sprintf('%.1fMB', memory_get_peak_usage()/1024/1024), ColorInterface::YELLOW);
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
        $categories = preg_replace('/[^0-9]/','',$this->params('categories', 1));

        $start = microtime(true);
        $this->create($products, $categories);
        $end = microtime(true);

        $console = $this->getServiceLocator()->get('console');
        $console->write('Created '.number_format($products) .' sample products', ColorInterface::LIGHT_CYAN);
        echo "\n";
        $console->write('All Done. Took '.sprintf('%.4fs', $end - $start).'', ColorInterface::YELLOW);
        echo "\n";
        $console->write('Memory: '.sprintf('%.4fs', memory_get_peak_usage()).'', ColorInterface::YELLOW);
        echo "\n";
    }

    function create($products, $categories)
    {
        $colors = ['red','blue','green'];
        $sizes = ['small','medium','large'];

        $csv = "sku,active,name,attributes,categories\n";
        for($i=0; $i<$products; $i++) {
            $attributes = array();
            if(rand(1,2)==1) {
                $attributes['size'] = $sizes[rand(0,2)];
            }
            if(rand(1,2)==1) {
                $attributes['color'] = $colors[rand(0,2)];
            }

            $attributes = \Zend\Json\Json::encode($attributes);
            $attributes = str_replace('"', '\\"', $attributes);

            $category = rand(1,$categories);

            $csv.= "sku-$i,1,name-$i,\"$attributes\",category-$category\n";
        }

        $db = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $importer = new Importer($db);
        $importer->importFromText($csv);
    }
}