<?php
namespace Application;

class AttributeMapper
{
    protected $db;

    function __construct($db)
    {
        $this->db = $db;
    }

    function save($attribute)
    {
        $this->db->insert('attribute',array(
            'name'=>$attribute->name()
        ));
        $attribute_id = $this->db->lastInsertId();
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
            $this->db->insert('attribute_option',array(
                'attribute_id'=>$attribute_id,
                'name'=>$option,
            ));
            $attribute->setOptionId($option,$this->db->lastInsertId());
        }
    }

    function load($attribute_id)
    {
        $select = $this->db->select()
            ->from('attribute')
            ->where('id=?',$attribute_id)
            ->limit(1);
        $data = $select->query()->fetch();
        $attribute = new Attribute($data);
        $this->loadOptions($attribute_id, $attribute);
        return $attribute;
    }

    function loadOptions($attribute_id,$attribute)
    {
        $select = $this->db->select()
            ->from('attribute_option')
            ->where('attribute_id=?',$attribute_id);
        $result = $select->query();
        while($data = $result->fetch()) {
            $attribute->addOption($data['name']);
            $attribute->setOptionId($data['name'], $data['id']);
        }
    }
}