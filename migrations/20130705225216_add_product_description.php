<?php

use Phinx\Migration\AbstractMigration;

class AddProductDescription extends AbstractMigration
{
    /**
     * Change Method.
     */
    public function change()
    {
        $table = $this->table('product');
        $table->addColumn('description','text')
            ->save();
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