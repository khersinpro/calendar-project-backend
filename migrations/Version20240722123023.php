<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240722123023 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id INT NOT NULL, country_id INT DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, postal_code INT DEFAULT NULL, city VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D4E6F81F92F3E70 ON address (country_id)');
        $this->addSql('CREATE TABLE address_complement (id INT NOT NULL, address_id INT NOT NULL, complement VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_803996AAF5B7AF75 ON address_complement (address_id)');
        $this->addSql('CREATE TABLE country (id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_5373C9665E237E06 ON country (name)');
        $this->addSql('CREATE TABLE custom_planning_day (id INT NOT NULL, planning_id INT NOT NULL, date DATE NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9F7EF0B93D865311 ON custom_planning_day (planning_id)');
        $this->addSql('CREATE TABLE custom_working_hour (id INT NOT NULL, custom_planning_day_id INT NOT NULL, open_time DATE NOT NULL, close_time DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_55E3B4AFFCFBDB6 ON custom_working_hour (custom_planning_day_id)');
        $this->addSql('CREATE TABLE event_type (id INT NOT NULL, organization_id INT NOT NULL, name VARCHAR(255) NOT NULL, duration INT NOT NULL, price NUMERIC(10, 2) DEFAULT NULL, payment_required BOOLEAN NOT NULL, deposit_required BOOLEAN NOT NULL, deposit_amout NUMERIC(10, 2) DEFAULT NULL, address_required BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_93151B8232C8A3DE ON event_type (organization_id)');
        $this->addSql('CREATE TABLE event_type_organization_user (event_type_id INT NOT NULL, organization_user_id INT NOT NULL, PRIMARY KEY(event_type_id, organization_user_id))');
        $this->addSql('CREATE INDEX IDX_F6BDB854401B253C ON event_type_organization_user (event_type_id)');
        $this->addSql('CREATE INDEX IDX_F6BDB8546ABC5BD6 ON event_type_organization_user (organization_user_id)');
        $this->addSql('CREATE TABLE organization (id INT NOT NULL, address_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, website_url VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_C1EE637CF5B7AF75 ON organization (address_id)');
        $this->addSql('CREATE TABLE organization_user (id INT NOT NULL, user_id INT NOT NULL, organization_id INT NOT NULL, planning_id INT NOT NULL, organization_role VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B49AE8D4A76ED395 ON organization_user (user_id)');
        $this->addSql('CREATE INDEX IDX_B49AE8D432C8A3DE ON organization_user (organization_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B49AE8D43D865311 ON organization_user (planning_id)');
        $this->addSql('CREATE TABLE phone_number (id INT NOT NULL, address_id INT NOT NULL, number INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6B01BC5BF5B7AF75 ON phone_number (address_id)');
        $this->addSql('CREATE TABLE planning (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE planning_day (id INT NOT NULL, planning_id INT NOT NULL, day_of_week VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E4F9E7A23D865311 ON planning_day (planning_id)');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)');
        $this->addSql('CREATE TABLE user_provider (id INT NOT NULL, user_id INT NOT NULL, type VARCHAR(255) NOT NULL, access_token VARCHAR(255) NOT NULL, refresh_token VARCHAR(255) DEFAULT NULL, token_expire DATE DEFAULT NULL, unique_id VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7249979CA76ED395 ON user_provider (user_id)');
        $this->addSql('CREATE TABLE working_hour (id INT NOT NULL, planning_day_id INT NOT NULL, open_time DATE NOT NULL, close_time DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2E64A3B469E36349 ON working_hour (planning_day_id)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F81F92F3E70 FOREIGN KEY (country_id) REFERENCES country (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE address_complement ADD CONSTRAINT FK_803996AAF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE custom_planning_day ADD CONSTRAINT FK_9F7EF0B93D865311 FOREIGN KEY (planning_id) REFERENCES planning (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE custom_working_hour ADD CONSTRAINT FK_55E3B4AFFCFBDB6 FOREIGN KEY (custom_planning_day_id) REFERENCES custom_planning_day (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_type ADD CONSTRAINT FK_93151B8232C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_type_organization_user ADD CONSTRAINT FK_F6BDB854401B253C FOREIGN KEY (event_type_id) REFERENCES event_type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE event_type_organization_user ADD CONSTRAINT FK_F6BDB8546ABC5BD6 FOREIGN KEY (organization_user_id) REFERENCES organization_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organization ADD CONSTRAINT FK_C1EE637CF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organization_user ADD CONSTRAINT FK_B49AE8D4A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organization_user ADD CONSTRAINT FK_B49AE8D432C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE organization_user ADD CONSTRAINT FK_B49AE8D43D865311 FOREIGN KEY (planning_id) REFERENCES planning (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE phone_number ADD CONSTRAINT FK_6B01BC5BF5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE planning_day ADD CONSTRAINT FK_E4F9E7A23D865311 FOREIGN KEY (planning_id) REFERENCES planning (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_provider ADD CONSTRAINT FK_7249979CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE working_hour ADD CONSTRAINT FK_2E64A3B469E36349 FOREIGN KEY (planning_day_id) REFERENCES planning_day (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE address DROP CONSTRAINT FK_D4E6F81F92F3E70');
        $this->addSql('ALTER TABLE address_complement DROP CONSTRAINT FK_803996AAF5B7AF75');
        $this->addSql('ALTER TABLE custom_planning_day DROP CONSTRAINT FK_9F7EF0B93D865311');
        $this->addSql('ALTER TABLE custom_working_hour DROP CONSTRAINT FK_55E3B4AFFCFBDB6');
        $this->addSql('ALTER TABLE event_type DROP CONSTRAINT FK_93151B8232C8A3DE');
        $this->addSql('ALTER TABLE event_type_organization_user DROP CONSTRAINT FK_F6BDB854401B253C');
        $this->addSql('ALTER TABLE event_type_organization_user DROP CONSTRAINT FK_F6BDB8546ABC5BD6');
        $this->addSql('ALTER TABLE organization DROP CONSTRAINT FK_C1EE637CF5B7AF75');
        $this->addSql('ALTER TABLE organization_user DROP CONSTRAINT FK_B49AE8D4A76ED395');
        $this->addSql('ALTER TABLE organization_user DROP CONSTRAINT FK_B49AE8D432C8A3DE');
        $this->addSql('ALTER TABLE organization_user DROP CONSTRAINT FK_B49AE8D43D865311');
        $this->addSql('ALTER TABLE phone_number DROP CONSTRAINT FK_6B01BC5BF5B7AF75');
        $this->addSql('ALTER TABLE planning_day DROP CONSTRAINT FK_E4F9E7A23D865311');
        $this->addSql('ALTER TABLE user_provider DROP CONSTRAINT FK_7249979CA76ED395');
        $this->addSql('ALTER TABLE working_hour DROP CONSTRAINT FK_2E64A3B469E36349');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE address_complement');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE custom_planning_day');
        $this->addSql('DROP TABLE custom_working_hour');
        $this->addSql('DROP TABLE event_type');
        $this->addSql('DROP TABLE event_type_organization_user');
        $this->addSql('DROP TABLE organization');
        $this->addSql('DROP TABLE organization_user');
        $this->addSql('DROP TABLE phone_number');
        $this->addSql('DROP TABLE planning');
        $this->addSql('DROP TABLE planning_day');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE user_provider');
        $this->addSql('DROP TABLE working_hour');
    }
}
