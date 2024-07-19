<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240719122549 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_provider AS SELECT id, user_id_id, type, access_token, refresh_token, token_expire, unique_id FROM user_provider');
        $this->addSql('DROP TABLE user_provider');
        $this->addSql('CREATE TABLE user_provider (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id_id INTEGER NOT NULL, type VARCHAR(255) NOT NULL, access_token VARCHAR(255) NOT NULL, refresh_token VARCHAR(255) DEFAULT NULL, token_expire DATE DEFAULT NULL, unique_id VARCHAR(255) NOT NULL, CONSTRAINT FK_7249979C9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user_provider (id, user_id_id, type, access_token, refresh_token, token_expire, unique_id) SELECT id, user_id_id, type, access_token, refresh_token, token_expire, unique_id FROM __temp__user_provider');
        $this->addSql('DROP TABLE __temp__user_provider');
        $this->addSql('CREATE INDEX IDX_7249979C9D86650F ON user_provider (user_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__user_provider AS SELECT id, user_id_id, type, access_token, refresh_token, token_expire, unique_id FROM user_provider');
        $this->addSql('DROP TABLE user_provider');
        $this->addSql('CREATE TABLE user_provider (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id_id INTEGER NOT NULL, type VARCHAR(255) NOT NULL, access_token VARCHAR(255) NOT NULL, refresh_token VARCHAR(255) DEFAULT NULL, token_expire DATE DEFAULT NULL, unique_id INTEGER NOT NULL, CONSTRAINT FK_7249979C9D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO user_provider (id, user_id_id, type, access_token, refresh_token, token_expire, unique_id) SELECT id, user_id_id, type, access_token, refresh_token, token_expire, unique_id FROM __temp__user_provider');
        $this->addSql('DROP TABLE __temp__user_provider');
        $this->addSql('CREATE INDEX IDX_7249979C9D86650F ON user_provider (user_id_id)');
    }
}
