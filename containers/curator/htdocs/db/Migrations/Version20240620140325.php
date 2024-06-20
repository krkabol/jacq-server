<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240620140325 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Initial db schema';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE photos (id INT AUTO_INCREMENT NOT NULL, `key` VARCHAR(255) NOT NULL, width INT DEFAULT NULL, height INT DEFAULT NULL, specimen_id VARCHAR(255) DEFAULT NULL, herbarium VARCHAR(255) DEFAULT NULL, msg LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_876E0D98A90ABA9 (`key`), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE photos');
    }
}
