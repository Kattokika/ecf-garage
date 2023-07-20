<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230718211621 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicule ADD thumbnail_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicule ADD CONSTRAINT FK_292FFF1DFDFF2E92 FOREIGN KEY (thumbnail_id) REFERENCES vehicule_photo (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_292FFF1DFDFF2E92 ON vehicule (thumbnail_id)');
        $this->addSql('ALTER TABLE vehicule_photo DROP principale');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE vehicule DROP CONSTRAINT FK_292FFF1DFDFF2E92');
        $this->addSql('DROP INDEX UNIQ_292FFF1DFDFF2E92');
        $this->addSql('ALTER TABLE vehicule DROP thumbnail_id');
        $this->addSql('ALTER TABLE vehicule_photo ADD principale BOOLEAN NOT NULL');
    }
}
