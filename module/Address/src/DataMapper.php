<?php
namespace Metator\Address;

use Zend\Db\TableGateway\TableGateway;

class DataMapper
{
    protected $db, $table;

    function __construct($db)
    {
        $this->db = $db;
        $this->table = new TableGateway('address', $this->db);
    }

    function save(&$address)
    {
        if(isset($address['id']) && $address['id']) {
            $this->table->update($address,array('id'=>$address['id']));
        } else {
            $this->table->insert($address);
            $address['id'] =  $this->table->getLastInsertValue();
        }
        return $address['id'];
    }

    function load($id)
    {
        $rowset = $this->table->select(array(
            'id'=>$id
        ));
        $data = (array)$rowset->current();
        return $data;
    }
}