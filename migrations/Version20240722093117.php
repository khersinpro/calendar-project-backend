<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240722093117 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event_type_organization_user (event_type_id INTEGER NOT NULL, organization_user_id INTEGER NOT NULL, PRIMARY KEY(event_type_id, organization_user_id), CONSTRAINT FK_F6BDB854401B253C FOREIGN KEY (event_type_id) REFERENCES event_type (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F6BDB8546ABC5BD6 FOREIGN KEY (organization_user_id) REFERENCES organization_user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F6BDB854401B253C ON event_type_organization_user (event_type_id)');
        $this->addSql('CREATE INDEX IDX_F6BDB8546ABC5BD6 ON event_type_organization_user (organization_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE event_type_organization_user');
    }
}
