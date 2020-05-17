<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200510172618 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mod_lists (id VARCHAR(36) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mod_lists_mods (mod_list_id VARCHAR(36) NOT NULL, mod_id VARCHAR(36) NOT NULL, INDEX IDX_77414C92FD60CD19 (mod_list_id), INDEX IDX_77414C92338E21CD (mod_id), PRIMARY KEY(mod_list_id, mod_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mods (id VARCHAR(36) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, type VARCHAR(255) NOT NULL COMMENT \'(DC2Type:mod_type_enum)\', source VARCHAR(255) NOT NULL, item_id INT DEFAULT NULL, directory VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_631EF2FA126F525E (item_id), UNIQUE INDEX UNIQ_631EF2FA467844DA (directory), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mod_lists_mods ADD CONSTRAINT FK_77414C92FD60CD19 FOREIGN KEY (mod_list_id) REFERENCES mod_lists (id)');
        $this->addSql('ALTER TABLE mod_lists_mods ADD CONSTRAINT FK_77414C92338E21CD FOREIGN KEY (mod_id) REFERENCES mods (id)');
        $this->addSql('ALTER TABLE permissions CHANGE users_manage_permissions user_manage_permissions TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE permissions CHANGE users_list user_list TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE permissions CHANGE users_delete user_delete TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE permissions ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD last_updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD mod_list TINYINT(1) NOT NULL, ADD mod_create TINYINT(1) NOT NULL, ADD mod_update TINYINT(1) NOT NULL, ADD mod_delete TINYINT(1) NOT NULL, ADD mod_list_list TINYINT(1) NOT NULL, ADD mod_list_create TINYINT(1) NOT NULL, ADD mod_list_update TINYINT(1) NOT NULL, ADD mod_list_delete TINYINT(1) NOT NULL, ADD mod_list_copy TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE users ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD last_updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mod_lists_mods DROP FOREIGN KEY FK_77414C92FD60CD19');
        $this->addSql('ALTER TABLE mod_lists_mods DROP FOREIGN KEY FK_77414C92338E21CD');
        $this->addSql('DROP TABLE mod_lists');
        $this->addSql('DROP TABLE mod_lists_mods');
        $this->addSql('DROP TABLE mods');
        $this->addSql('ALTER TABLE permissions CHANGE user_manage_permissions users_manage_permissions TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE permissions CHANGE user_list users_list TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE permissions CHANGE user_delete users_delete TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE permissions DROP created_at, DROP last_updated_at, DROP mod_list, DROP mod_create, DROP mod_update, DROP mod_delete, DROP mod_list_list, DROP mod_list_create, DROP mod_list_update, DROP mod_list_delete, DROP mod_list_copy');
        $this->addSql('ALTER TABLE users DROP created_at, DROP last_updated_at');
    }
}
