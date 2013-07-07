<?php

use Phinx\Migration\AbstractMigration;

class AddOrderTable extends AbstractMigration
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
        $this->execute('CREATE TABLE IF NOT EXISTS `order` (
  `id` int(11) NOT NULL,
  `created` datetime NOT NULL,
  `shipping` int(10) NOT NULL,
  `billing` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;
');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}