<?php
namespace Application\Controller;

use Zend\Console\Request as ConsoleRequest,
    Zend\Mvc\Controller\AbstractActionController,
    Zend\Console\ColorInterface;
use \Application\Importer;

class ConsoleController extends AbstractActionController
{

    function sampleproductsAction()
    {
        /**
         * Enforce valid console request
         */
        $request = $this->getRequest();
        if (!$request instanceof ConsoleRequest) {
            throw new \RuntimeException('You can only use this action from a console!');
        }

        $csv = "sku,name\n";
        for($i=0; $i<100000; $i++) {
            $csv.= "sku-$i,name-$i\n";
        }

        $db = $this->getServiceLocator()->get('Zend\Db\Adapter\Adapter');
        $importer = new Importer($db);
        $importer->importFromText($csv);

        return 'got here';
    }
}