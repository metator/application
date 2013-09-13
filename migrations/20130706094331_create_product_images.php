<?php

use Phinx\Migration\AbstractMigration;

class CreateProductImages extends AbstractMigration
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
        $this->execute('
CREATE TABLE IF NOT EXISTS `product_images` (
  `product_id` int(15) NOT NULL,
  `image_hash` varchar(40) NOT NULL,
  PRIMARY KEY (`product_id`,`image_hash`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        throw new Exception('no down method');
    }
}