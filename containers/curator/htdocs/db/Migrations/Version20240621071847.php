<?php

declare(strict_types=1);

namespace Database\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240621071847 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'initial db schema';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE herbaria (id INT AUTO_INCREMENT NOT NULL, acronym VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_40DF22BA512D8851 (acronym), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE photos (id INT AUTO_INCREMENT NOT NULL, herbarium_id INT DEFAULT NULL, `key` VARCHAR(255) NOT NULL, width INT DEFAULT NULL, height INT DEFAULT NULL, specimen_id VARCHAR(255) DEFAULT NULL, finalized TINYINT(1) NOT NULL, msg LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_876E0D98A90ABA9 (`key`), INDEX IDX_876E0D9DD127992 (herbarium_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE photos ADD CONSTRAINT FK_876E0D9DD127992 FOREIGN KEY (herbarium_id) REFERENCES herbaria (id)');
        $this->addSql("INSERT INTO herbaria VALUES (DEFAULT, 'PRC')");
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE photos DROP FOREIGN KEY FK_876E0D9DD127992');
        $this->addSql('DROP TABLE herbaria');
        $this->addSql('DROP TABLE photos');
    }
}
