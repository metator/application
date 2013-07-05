<?php
namespace Application;
use Zend\Db\Sql\Sql;
use Zend\Db\TableGateway\TableGateway;

class AttributeMapper
{
    protected $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    function save($attribute)
    {
        $adapter = $this->db;

        $sql = new Sql($adapter);
        $insert = $sql->insert('attribute')
        ->values(array(
            'name'=>$attribute->name()
        ));
        $string = $sql->getSqlStringForSqlObject($insert);
        $adapter->query($string, $adapter::QUERY_MODE_EXECUTE);

        $attribute_id = $adapter->getDriver()->getLastGeneratedValue();
        $attribute->setId($attribute_id);
        $this->saveOptions($attribute_id,$attribute);
        return $attribute_id;
    }

    /**
     * @param $attribute_id
     * @param $attribute Attribute
     */
    function saveOptions($attribute_id,$attribute)
    {
        foreach($attribute->options() as $option)
        {
            $adapter = $this->db;

            $sql = new Sql($adapter);
            $insert = $sql
                ->insert('attribute_option')
                ->values(array(
                    'attribute_id'=>$attribute_id,
                    'name'=>$option,
                ));

            $string = $sql->getSqlStringForSqlObject($insert);
            $adapter->query($string, $adapter::QUERY_MODE_EXECUTE);

            $id = $adapter->getDriver()->getLastGeneratedValue();
            $attribute->setOptionId($option,$id);
        }
    }

    function load($attribute_id)
    {
        $adapter = $this->db;

        $sql = new Sql($adapter);
        $select = $sql
            ->select()
            ->from('attribute')
            ->where(array('id'=>$attribute_id))
            ->limit(1);
        $string = $sql->getSqlStringForSqlObject($select);
        $data = $adapter->query($string, $adapter::QUERY_MODE_EXECUTE)->toArray()[0];

        $attribute = new Attribute($data);
        $this->loadOptions($attribute_id, $attribute);
        return $attribute;
    }

    function loadOptions($attribute_id,$attribute)
    {
        $table = new TableGateway('attribute_option', $this->db);
        $rowset = $table->select(array('attribute_id'=>$attribute_id));

        while($data = $rowset->current()) {
            $attribute->addOption($data['name']);
            $attribute->setOptionId($data['name'], $data['id']);
        }
    }
}