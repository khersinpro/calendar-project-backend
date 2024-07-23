<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240723091330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE organization_user DROP CONSTRAINT fk_b49ae8d43d865311');
        $this->addSql('ALTER TABLE custom_working_hour DROP CONSTRAINT fk_55e3b4affcfbdb6');
        $this->addSql('ALTER TABLE working_hour DROP CONSTRAINT fk_2e64a3b469e36349');
        $this->addSql('DROP SEQUENCE custom_planning_day_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE planning_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE planning_day_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE planning_event_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE custom_schedule_day_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE schedule_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE schedule_day_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE schedule_event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE custom_schedule_day (id INT NOT NULL, schedule_id INT NOT NULL, date DATE NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3EE7B81A40BC2D5 ON custom_schedule_day (schedule_id)');
        $this->addSql('CREATE TABLE schedule (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE schedule_day (id INT NOT NULL, schedule_id INT NOT NULL, day_of_week VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_78696C9AA40BC2D5 ON schedule_day (schedule_id)');
        $this->addSql('CREATE TABLE schedule_event (id INT NOT NULL, schedule_id INT NOT NULL, event_type_id INT NOT NULL, adress_id INT NOT NULL, customer_id INT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, status VARCHAR(255) NOT NULL, guest_first_name VARCHAR(255) DEFAULT NULL, guest_last_name VARCHAR(255) DEFAULT NULL, guest_phone INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C7F7CAFBA40BC2D5 ON schedule_event (schedule_id)');
        $this->addSql('CREATE INDEX IDX_C7F7CAFB401B253C ON schedule_event (event_type_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C7F7CAFB8486F9AC ON schedule_event (adress_id)');
        $this->addSql('CREATE INDEX IDX_C7F7CAFB9395C3F3 ON schedule_event (customer_id)');
        $this->addSql('ALTER TABLE custom_schedule_day ADD CONSTRAINT FK_3EE7B81A40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule_day ADD CONSTRAINT FK_78696C9AA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule_event ADD CONSTRAINT FK_C7F7CAFBA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule_event ADD CONSTRAINT FK_C7F7CAFB401B253C FOREIGN KEY (event_type_id) REFERENCES event_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule_event ADD CONSTRAINT FK_C7F7CAFB8486F9AC FOREIGN KEY (adress_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE schedule_event ADD CONSTRAINT FK_C7F7CAFB9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE planning_event DROP CONSTRAINT fk_aa02b0383d865311');
        $this->addSql('ALTER TABLE planning_event DROP CONSTRAINT fk_aa02b038401b253c');
        $this->addSql('ALTER TABLE planning_event DROP CONSTRAINT fk_aa02b0388486f9ac');
        $this->addSql('ALTER TABLE planning_event DROP CONSTRAINT fk_aa02b0389395c3f3');
        $this->addSql('ALTER TABLE custom_planning_day DROP CONSTRAINT fk_9f7ef0b93d865311');
        $this->addSql('ALTER TABLE planning_day DROP CONSTRAINT fk_e4f9e7a23d865311');
        $this->addSql('DROP TABLE planning_event');
        $this->addSql('DROP TABLE planning');
        $this->addSql('DROP TABLE custom_planning_day');
        $this->addSql('DROP TABLE planning_day');
        $this->addSql('DROP INDEX idx_55e3b4affcfbdb6');
        $this->addSql('ALTER TABLE custom_working_hour RENAME COLUMN custom_planning_day_id TO custom_schedule_day_id');
        $this->addSql('ALTER TABLE custom_working_hour ADD CONSTRAINT FK_55E3B4AFDCFDBC6 FOREIGN KEY (custom_schedule_day_id) REFERENCES custom_schedule_day (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_55E3B4AFDCFDBC6 ON custom_working_hour (custom_schedule_day_id)');
        $this->addSql('DROP INDEX uniq_b49ae8d43d865311');
        $this->addSql('ALTER TABLE organization_user RENAME COLUMN planning_id TO schedule_id');
        $this->addSql('ALTER TABLE organization_user ADD CONSTRAINT FK_B49AE8D4A40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B49AE8D4A40BC2D5 ON organization_user (schedule_id)');
        $this->addSql('DROP INDEX idx_2e64a3b469e36349');
        $this->addSql('ALTER TABLE working_hour RENAME COLUMN planning_day_id TO schedule_day_id');
        $this->addSql('ALTER TABLE working_hour ADD CONSTRAINT FK_2E64A3B46BE30539 FOREIGN KEY (schedule_day_id) REFERENCES schedule_day (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2E64A3B46BE30539 ON working_hour (schedule_day_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE custom_working_hour DROP CONSTRAINT FK_55E3B4AFDCFDBC6');
        $this->addSql('ALTER TABLE organization_user DROP CONSTRAINT FK_B49AE8D4A40BC2D5');
        $this->addSql('ALTER TABLE working_hour DROP CONSTRAINT FK_2E64A3B46BE30539');
        $this->addSql('DROP SEQUENCE custom_schedule_day_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE schedule_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE schedule_day_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE schedule_event_id_seq CASCADE');
        $this->addSql('CREATE SEQUENCE custom_planning_day_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE planning_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE planning_day_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE planning_event_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE planning_event (id INT NOT NULL, planning_id INT NOT NULL, event_type_id INT NOT NULL, adress_id INT NOT NULL, customer_id INT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, status VARCHAR(255) NOT NULL, guest_first_name VARCHAR(255) DEFAULT NULL, guest_last_name VARCHAR(255) DEFAULT NULL, guest_phone INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_aa02b0389395c3f3 ON planning_event (customer_id)');
        $this->addSql('CREATE UNIQUE INDEX uniq_aa02b0388486f9ac ON planning_event (adress_id)');
        $this->addSql('CREATE INDEX idx_aa02b038401b253c ON planning_event (event_type_id)');
        $this->addSql('CREATE INDEX idx_aa02b0383d865311 ON planning_event (planning_id)');
        $this->addSql('CREATE TABLE planning (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE custom_planning_day (id INT NOT NULL, planning_id INT NOT NULL, date DATE NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_9f7ef0b93d865311 ON custom_planning_day (planning_id)');
        $this->addSql('CREATE TABLE planning_day (id INT NOT NULL, planning_id INT NOT NULL, day_of_week VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX idx_e4f9e7a23d865311 ON planning_day (planning_id)');
        $this->addSql('ALTER TABLE planning_event ADD CONSTRAINT fk_aa02b0383d865311 FOREIGN KEY (planning_id) REFERENCES planning (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE planning_event ADD CONSTRAINT fk_aa02b038401b253c FOREIGN KEY (event_type_id) REFERENCES event_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE planning_event ADD CONSTRAINT fk_aa02b0388486f9ac FOREIGN KEY (adress_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE planning_event ADD CONSTRAINT fk_aa02b0389395c3f3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE custom_planning_day ADD CONSTRAINT fk_9f7ef0b93d865311 FOREIGN KEY (planning_id) REFERENCES planning (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE planning_day ADD CONSTRAINT fk_e4f9e7a23d865311 FOREIGN KEY (planning_id) REFERENCES planning (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE custom_schedule_day DROP CONSTRAINT FK_3EE7B81A40BC2D5');
        $this->addSql('ALTER TABLE schedule_day DROP CONSTRAINT FK_78696C9AA40BC2D5');
        $this->addSql('ALTER TABLE schedule_event DROP CONSTRAINT FK_C7F7CAFBA40BC2D5');
        $this->addSql('ALTER TABLE schedule_event DROP CONSTRAINT FK_C7F7CAFB401B253C');
        $this->addSql('ALTER TABLE schedule_event DROP CONSTRAINT FK_C7F7CAFB8486F9AC');
        $this->addSql('ALTER TABLE schedule_event DROP CONSTRAINT FK_C7F7CAFB9395C3F3');
        $this->addSql('DROP TABLE custom_schedule_day');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP TABLE schedule_day');
        $this->addSql('DROP TABLE schedule_event');
        $this->addSql('DROP INDEX IDX_55E3B4AFDCFDBC6');
        $this->addSql('ALTER TABLE custom_working_hour RENAME COLUMN custom_schedule_day_id TO custom_planning_day_id');
        $this->addSql('ALTER TABLE custom_working_hour ADD CONSTRAINT fk_55e3b4affcfbdb6 FOREIGN KEY (custom_planning_day_id) REFERENCES custom_planning_day (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_55e3b4affcfbdb6 ON custom_working_hour (custom_planning_day_id)');
        $this->addSql('DROP INDEX IDX_2E64A3B46BE30539');
        $this->addSql('ALTER TABLE working_hour RENAME COLUMN schedule_day_id TO planning_day_id');
        $this->addSql('ALTER TABLE working_hour ADD CONSTRAINT fk_2e64a3b469e36349 FOREIGN KEY (planning_day_id) REFERENCES planning_day (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_2e64a3b469e36349 ON working_hour (planning_day_id)');
        $this->addSql('DROP INDEX UNIQ_B49AE8D4A40BC2D5');
        $this->addSql('ALTER TABLE organization_user RENAME COLUMN schedule_id TO planning_id');
        $this->addSql('ALTER TABLE organization_user ADD CONSTRAINT fk_b49ae8d43d865311 FOREIGN KEY (planning_id) REFERENCES planning (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_b49ae8d43d865311 ON organization_user (planning_id)');
    }
}
