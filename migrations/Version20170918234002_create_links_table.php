<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Create the links table
 */
class Version20170918234002_create_links_table extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->skipIf($schema->hasTable('links'));

        $table = $schema->createTable('links');
        $table->addColumn('id', 'integer', [
            'unsigned' => true,
            'autoincrement' => true,
        ]);
        $table->addColumn('md5', 'string', [
            'length' => 32,
        ]);
        $table->addColumn('url', 'string', [
            'length' => 2000,
        ]);

        $table->setPrimaryKey(['id']);
        $table->addUniqueIndex(['md5']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('links');
    }
}
