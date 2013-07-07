<?php

use Phinx\Migration\AbstractMigration;

class MakeOrderIdAutoIncrement extends AbstractMigration
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
        $this->execute('ALTER TABLE  `order` ADD PRIMARY KEY (  `id` )');
        $this->execute('ALTER TABLE  `order` CHANGE  `id`  `id` INT( 11 ) NOT NULL AUTO_INCREMENT');
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