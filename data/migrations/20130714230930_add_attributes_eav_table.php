<?php

use Phinx\Migration\AbstractMigration;

class AddAttributesEavTable extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     *
    public function change()
    {
    }
    */
    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute('CREATE TABLE IF NOT EXISTS `attributes_eav` (
  `attribute` varchar(50) NOT NULL,
  `value` varchar(50) NOT NULL,
  `product_id` int(50) NOT NULL,
  KEY `attribute` (`attribute`,`value`),
  KEY `attribute_2` (`attribute`,`product_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}