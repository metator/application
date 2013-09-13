<?php

use Phinx\Migration\AbstractMigration;

class MakeOrderAddressesNullable extends AbstractMigration
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
        $this->execute('ALTER TABLE  `order` CHANGE  `shipping`  `shipping` INT( 10 ) NULL');
        $this->execute('ALTER TABLE  `order` CHANGE  `billing`  `billing` INT( 10 ) NULL');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}