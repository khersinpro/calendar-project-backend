<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240724085700 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_type ADD reservation_payment_condition VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE event_type DROP payment_required');
        $this->addSql('ALTER TABLE event_type DROP deposit_required');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE event_type ADD payment_required BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE event_type ADD deposit_required BOOLEAN NOT NULL');
        $this->addSql('ALTER TABLE event_type DROP reservation_payment_condition');
    }
}
