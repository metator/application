<?php

use Phinx\Migration\AbstractMigration;

class ChangeCategoryStructureToMaterialPath extends AbstractMigration
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
        // remove fk restraints, & change it to a text field so it can hold the path like "1/2/3".
        $this->execute("ALTER TABLE  `metator`.`category_structure` DROP PRIMARY KEY ,
ADD PRIMARY KEY (  `category_id` );");

        $this->execute("ALTER TABLE  `category_structure` DROP FOREIGN KEY  `category_structure_ibfk_1` ;");
        $this->execute("ALTER TABLE  `category_structure` DROP FOREIGN KEY  `category_structure_ibfk_2` ;");
        $this->execute("ALTER TABLE category_structure DROP PRIMARY KEY;");
        $this->execute("ALTER TABLE category_structure DROP INDEX parent_id;");

        $this->execute("ALTER TABLE  `category_structure` CHANGE  `parent_id`  `path` VARCHAR( 150 ) NOT NULL;");
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}