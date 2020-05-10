<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200123221244 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create permissions table';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DELETE FROM users');
        $this->addSql('CREATE TABLE permissions (id VARCHAR(36) NOT NULL, manage_users_permissions TINYINT(1) NOT NULL, list_users TINYINT(1) NOT NULL, delete_users TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users ADD permissions_id VARCHAR(36) NOT NULL');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E99C3E4F87 FOREIGN KEY (permissions_id) REFERENCES permissions (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E99C3E4F87 ON users (permissions_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E99C3E4F87');
        $this->addSql('DROP TABLE permissions');
        $this->addSql('DROP INDEX UNIQ_1483A5E99C3E4F87 ON users');
        $this->addSql('ALTER TABLE users DROP permissions_id');
    }
}
