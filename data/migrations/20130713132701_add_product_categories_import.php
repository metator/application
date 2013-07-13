<?php

use Phinx\Migration\AbstractMigration;

class AddProductCategoriesImport extends AbstractMigration
{
    /**
     * Change Method.
     *
     * More information on this method is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-change-method
     *
     * Uncomment this method if you would like to use it.
     */
    public function change()
    {
        $this->execute('CREATE TABLE IF NOT EXISTS `product_categories_import` (
  `product_id` int(50) NOT NULL,
  `product_sku` varchar(150) NOT NULL,
  `category_id` int(50) NOT NULL,
  `category_name` varchar(150) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
');
    }
    
    /**
     * Migrate Up.
     */
    public function up()
    {
    
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}