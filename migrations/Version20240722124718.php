<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240722124718 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE customer_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE planning_event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE customer (id INT NOT NULL, organization_id INT NOT NULL, email VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, phone INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_81398E0932C8A3DE ON customer (organization_id)');
        $this->addSql('CREATE TABLE planning_event (id INT NOT NULL, planning_id INT NOT NULL, event_type_id INT NOT NULL, adress_id INT NOT NULL, customer_id INT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, status VARCHAR(255) NOT NULL, guest_first_name VARCHAR(255) DEFAULT NULL, guest_last_name VARCHAR(255) DEFAULT NULL, guest_phone INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_AA02B0383D865311 ON planning_event (planning_id)');
        $this->addSql('CREATE INDEX IDX_AA02B038401B253C ON planning_event (event_type_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AA02B0388486F9AC ON planning_event (adress_id)');
        $this->addSql('CREATE INDEX IDX_AA02B0389395C3F3 ON planning_event (customer_id)');
        $this->addSql('ALTER TABLE customer ADD CONSTRAINT FK_81398E0932C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE planning_event ADD CONSTRAINT FK_AA02B0383D865311 FOREIGN KEY (planning_id) REFERENCES planning (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE planning_event ADD CONSTRAINT FK_AA02B038401B253C FOREIGN KEY (event_type_id) REFERENCES event_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE planning_event ADD CONSTRAINT FK_AA02B0388486F9AC FOREIGN KEY (adress_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE planning_event ADD CONSTRAINT FK_AA02B0389395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE customer_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE planning_event_id_seq CASCADE');
        $this->addSql('ALTER TABLE customer DROP CONSTRAINT FK_81398E0932C8A3DE');
        $this->addSql('ALTER TABLE planning_event DROP CONSTRAINT FK_AA02B0383D865311');
        $this->addSql('ALTER TABLE planning_event DROP CONSTRAINT FK_AA02B038401B253C');
        $this->addSql('ALTER TABLE planning_event DROP CONSTRAINT FK_AA02B0388486F9AC');
        $this->addSql('ALTER TABLE planning_event DROP CONSTRAINT FK_AA02B0389395C3F3');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE planning_event');
    }
}
