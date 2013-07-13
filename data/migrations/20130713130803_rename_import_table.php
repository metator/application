<?php

use Phinx\Migration\AbstractMigration;

class RenameImportTable extends AbstractMigration
{

    
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute('RENAME TABLE  `import` TO  `product_import` ;');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}