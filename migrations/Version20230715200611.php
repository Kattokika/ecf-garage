<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230715200611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX uniq_292fff1db5b48b91');
        $this->addSql('ALTER TABLE vehicule ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE vehicule DROP public_id');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_292FFF1D989D9B62 ON vehicule (slug)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP INDEX UNIQ_292FFF1D989D9B62');
        $this->addSql('ALTER TABLE vehicule ADD public_id UUID NOT NULL');
        $this->addSql('ALTER TABLE vehicule DROP slug');
        $this->addSql('CREATE UNIQUE INDEX uniq_292fff1db5b48b91 ON vehicule (public_id)');
    }
}
