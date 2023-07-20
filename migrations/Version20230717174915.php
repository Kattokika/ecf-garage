<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230717174915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE vehicule ADD marque VARCHAR(64) NOT NULL');
        $this->addSql('ALTER TABLE vehicule ADD modele VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE vehicule ADD kilometre INT NOT NULL');
        $this->addSql('ALTER TABLE vehicule ADD prix INT NOT NULL');
        $this->addSql('ALTER TABLE vehicule ADD annee SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE vehicule ADD boite VARCHAR(32) NOT NULL');
        $this->addSql('ALTER TABLE vehicule ADD portes VARCHAR(2) NOT NULL');
        $this->addSql('ALTER TABLE vehicule ADD couleur VARCHAR(32) NOT NULL');
        $this->addSql('ALTER TABLE vehicule ADD puissance SMALLINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE vehicule DROP marque');
        $this->addSql('ALTER TABLE vehicule DROP modele');
        $this->addSql('ALTER TABLE vehicule DROP kilometre');
        $this->addSql('ALTER TABLE vehicule DROP prix');
        $this->addSql('ALTER TABLE vehicule DROP annee');
        $this->addSql('ALTER TABLE vehicule DROP boite');
        $this->addSql('ALTER TABLE vehicule DROP portes');
        $this->addSql('ALTER TABLE vehicule DROP couleur');
        $this->addSql('ALTER TABLE vehicule DROP puissance');
    }
}
