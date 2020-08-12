<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200125110932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Migrate users permissions to embeddable';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE permissions CHANGE manage_users_permissions users_manage_permissions TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE permissions CHANGE list_users users_list TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE permissions CHANGE delete_users users_delete TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE permissions CHANGE users_manage_permissions manage_users_permissions TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE permissions CHANGE user_manage_permissions users_list list_users TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE permissions CHANGE user_manage_permissions users_delete delete_users TINYINT(1) NOT NULL');
    }
}
