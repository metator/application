<?php

use Phinx\Migration\AbstractMigration;

class MakeAttributesUnique extends AbstractMigration
{

    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->execute('ALTER TABLE  `attribute` ADD UNIQUE (
`name`
)');
    }

    /**
     * Migrate Down.
     */
    public function down()
    {

    }
}