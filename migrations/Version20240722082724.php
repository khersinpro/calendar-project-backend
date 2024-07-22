<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240722082724 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE custom_planning_day (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, planning_id INTEGER NOT NULL, date DATE NOT NULL, status VARCHAR(255) NOT NULL, CONSTRAINT FK_9F7EF0B93D865311 FOREIGN KEY (planning_id) REFERENCES planning (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_9F7EF0B93D865311 ON custom_planning_day (planning_id)');
        $this->addSql('CREATE TABLE custom_working_hour (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, custom_planning_day_id INTEGER NOT NULL, open_time DATE NOT NULL, close_time DATE NOT NULL, CONSTRAINT FK_55E3B4AFFCFBDB6 FOREIGN KEY (custom_planning_day_id) REFERENCES custom_planning_day (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_55E3B4AFFCFBDB6 ON custom_working_hour (custom_planning_day_id)');
        $this->addSql('CREATE TABLE planning_day (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, planning_id INTEGER NOT NULL, day_of_week VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, CONSTRAINT FK_E4F9E7A23D865311 FOREIGN KEY (planning_id) REFERENCES planning (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_E4F9E7A23D865311 ON planning_day (planning_id)');
        $this->addSql('CREATE TABLE working_hour (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, planning_day_id INTEGER NOT NULL, open_time DATE NOT NULL, close_time DATE NOT NULL, CONSTRAINT FK_2E64A3B469E36349 FOREIGN KEY (planning_day_id) REFERENCES planning_day (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_2E64A3B469E36349 ON working_hour (planning_day_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE custom_planning_day');
        $this->addSql('DROP TABLE custom_working_hour');
        $this->addSql('DROP TABLE planning_day');
        $this->addSql('DROP TABLE working_hour');
    }
}
