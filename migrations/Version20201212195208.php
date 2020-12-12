<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201212195208 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Mod Group functionality';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE mod_groups (id VARCHAR(36) NOT NULL, created_by VARCHAR(36) DEFAULT NULL, last_updated_by VARCHAR(36) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_84DA060FDE12AB56 (created_by), INDEX IDX_84DA060FFF8A180B (last_updated_by), INDEX IDX_84DA060F8B8E8428 (created_at), INDEX IDX_84DA060FAA163775 (last_updated_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mod_group_mods (mod_group_id VARCHAR(36) NOT NULL, mod_id VARCHAR(36) NOT NULL, INDEX IDX_AAC336B7E095E5F4 (mod_group_id), INDEX IDX_AAC336B7338E21CD (mod_id), PRIMARY KEY(mod_group_id, mod_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mod_lists_mod_groups (mod_list_id VARCHAR(36) NOT NULL, mod_group_id VARCHAR(36) NOT NULL, INDEX IDX_47CB2915FD60CD19 (mod_list_id), INDEX IDX_47CB2915E095E5F4 (mod_group_id), PRIMARY KEY(mod_list_id, mod_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE mod_groups ADD CONSTRAINT FK_84DA060FDE12AB56 FOREIGN KEY (created_by) REFERENCES users (id)');
        $this->addSql('ALTER TABLE mod_groups ADD CONSTRAINT FK_84DA060FFF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id)');
        $this->addSql('ALTER TABLE mod_group_mods ADD CONSTRAINT FK_AAC336B7E095E5F4 FOREIGN KEY (mod_group_id) REFERENCES mod_groups (id)');
        $this->addSql('ALTER TABLE mod_group_mods ADD CONSTRAINT FK_AAC336B7338E21CD FOREIGN KEY (mod_id) REFERENCES mods (id)');
        $this->addSql('ALTER TABLE mod_lists_mod_groups ADD CONSTRAINT FK_47CB2915FD60CD19 FOREIGN KEY (mod_list_id) REFERENCES mod_lists (id)');
        $this->addSql('ALTER TABLE mod_lists_mod_groups ADD CONSTRAINT FK_47CB2915E095E5F4 FOREIGN KEY (mod_group_id) REFERENCES mod_groups (id)');
        $this->addSql('ALTER TABLE permissions ADD mod_group_list TINYINT(1) NOT NULL, ADD mod_group_create TINYINT(1) NOT NULL, ADD mod_group_update TINYINT(1) NOT NULL, ADD mod_group_delete TINYINT(1) NOT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_84DA060F5E237E06 ON mod_groups (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mod_group_mods DROP FOREIGN KEY FK_AAC336B7E095E5F4');
        $this->addSql('ALTER TABLE mod_lists_mod_groups DROP FOREIGN KEY FK_47CB2915E095E5F4');
        $this->addSql('DROP TABLE mod_groups');
        $this->addSql('DROP TABLE mod_group_mods');
        $this->addSql('DROP TABLE mod_lists_mod_groups');
        $this->addSql('ALTER TABLE permissions DROP mod_group_list, DROP mod_group_create, DROP mod_group_update, DROP mod_group_delete');
        $this->addSql('DROP INDEX UNIQ_84DA060F5E237E06 ON mod_groups');
    }
}
