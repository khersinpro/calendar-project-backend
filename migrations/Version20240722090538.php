<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240722090538 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, organization_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, duration INTEGER NOT NULL, price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, payment_required BOOLEAN NOT NULL, deposit_required BOOLEAN NOT NULL, deposit_amout NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, address_required BOOLEAN NOT NULL, CONSTRAINT FK_93151B8232C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_93151B8232C8A3DE ON event_type (organization_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE event_type');
    }
}
