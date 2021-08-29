<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210827225825 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add edit user permissions';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE permissions CHANGE user_manage_permissions user_update TINYINT(1) NOT NULL');
        $this->addSql('DROP INDEX IDX_1483A5E9F85E0677 ON users');
        $this->addSql('DROP INDEX IDX_1483A5E99F75D7B0 ON users');
        $this->addSql('ALTER TABLE users ADD steam_id BIGINT DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F3FD4ECA ON users (steam_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE permissions CHANGE user_update user_manage_permissions TINYINT(1) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_1483A5E9F3FD4ECA ON users');
        $this->addSql('ALTER TABLE users DROP steam_id');
        $this->addSql('CREATE INDEX IDX_1483A5E9F85E0677 ON users (username)');
        $this->addSql('CREATE INDEX IDX_1483A5E99F75D7B0 ON users (external_id)');
    }
}
