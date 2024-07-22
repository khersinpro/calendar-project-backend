<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240722091722 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__event_type AS SELECT id, organization_id, name, duration, price, payment_required, deposit_required, deposit_amout, address_required FROM event_type');
        $this->addSql('DROP TABLE event_type');
        $this->addSql('CREATE TABLE event_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, organization_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, duration INTEGER NOT NULL, price NUMERIC(10, 2) DEFAULT NULL, payment_required BOOLEAN NOT NULL, deposit_required BOOLEAN NOT NULL, deposit_amout NUMERIC(10, 2) DEFAULT NULL, address_required BOOLEAN NOT NULL, CONSTRAINT FK_93151B8232C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event_type (id, organization_id, name, duration, price, payment_required, deposit_required, deposit_amout, address_required) SELECT id, organization_id, name, duration, price, payment_required, deposit_required, deposit_amout, address_required FROM __temp__event_type');
        $this->addSql('DROP TABLE __temp__event_type');
        $this->addSql('CREATE INDEX IDX_93151B8232C8A3DE ON event_type (organization_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__event_type AS SELECT id, organization_id, name, duration, price, payment_required, deposit_required, deposit_amout, address_required FROM event_type');
        $this->addSql('DROP TABLE event_type');
        $this->addSql('CREATE TABLE event_type (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, organization_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, duration INTEGER NOT NULL, price NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, payment_required BOOLEAN NOT NULL, deposit_required BOOLEAN NOT NULL, deposit_amout NUMERIC(10, 2) DEFAULT \'0\' NOT NULL, address_required BOOLEAN NOT NULL, CONSTRAINT FK_93151B8232C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event_type (id, organization_id, name, duration, price, payment_required, deposit_required, deposit_amout, address_required) SELECT id, organization_id, name, duration, price, payment_required, deposit_required, deposit_amout, address_required FROM __temp__event_type');
        $this->addSql('DROP TABLE __temp__event_type');
        $this->addSql('CREATE INDEX IDX_93151B8232C8A3DE ON event_type (organization_id)');
    }
}
