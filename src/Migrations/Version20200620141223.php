<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200620141223 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add blameable behavior';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mod_lists ADD created_by VARCHAR(36) DEFAULT NULL, ADD last_updated_by VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE mod_lists ADD CONSTRAINT FK_ECB7A26DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id)');
        $this->addSql('ALTER TABLE mod_lists ADD CONSTRAINT FK_ECB7A26FF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ECB7A26DE12AB56 ON mod_lists (created_by)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ECB7A26FF8A180B ON mod_lists (last_updated_by)');
        $this->addSql('ALTER TABLE mods ADD created_by VARCHAR(36) DEFAULT NULL, ADD last_updated_by VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE mods ADD CONSTRAINT FK_631EF2FADE12AB56 FOREIGN KEY (created_by) REFERENCES users (id)');
        $this->addSql('ALTER TABLE mods ADD CONSTRAINT FK_631EF2FAFF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_631EF2FADE12AB56 ON mods (created_by)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_631EF2FAFF8A180B ON mods (last_updated_by)');
        $this->addSql('ALTER TABLE permissions ADD created_by VARCHAR(36) DEFAULT NULL, ADD last_updated_by VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6FDE12AB56 FOREIGN KEY (created_by) REFERENCES users (id)');
        $this->addSql('ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6FFF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DEDCC6FDE12AB56 ON permissions (created_by)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2DEDCC6FFF8A180B ON permissions (last_updated_by)');
        $this->addSql('ALTER TABLE users ADD created_by VARCHAR(36) DEFAULT NULL, ADD last_updated_by VARCHAR(36) DEFAULT NULL, DROP password');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9FF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9DE12AB56 ON users (created_by)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9FF8A180B ON users (last_updated_by)');
        $this->addSql('CREATE INDEX IDX_1483A5E98B8E8428 ON users (created_at)');
        $this->addSql('CREATE INDEX IDX_1483A5E9F85E0677 ON users (username)');
        $this->addSql('ALTER TABLE mod_lists ADD owner_id VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE mod_lists ADD CONSTRAINT FK_ECB7A267E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_ECB7A267E3C61F9 ON mod_lists (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mod_lists DROP FOREIGN KEY FK_ECB7A26DE12AB56');
        $this->addSql('ALTER TABLE mod_lists DROP FOREIGN KEY FK_ECB7A26FF8A180B');
        $this->addSql('DROP INDEX UNIQ_ECB7A26DE12AB56 ON mod_lists');
        $this->addSql('DROP INDEX UNIQ_ECB7A26FF8A180B ON mod_lists');
        $this->addSql('ALTER TABLE mod_lists DROP created_by, DROP last_updated_by');
        $this->addSql('ALTER TABLE mods DROP FOREIGN KEY FK_631EF2FADE12AB56');
        $this->addSql('ALTER TABLE mods DROP FOREIGN KEY FK_631EF2FAFF8A180B');
        $this->addSql('DROP INDEX UNIQ_631EF2FADE12AB56 ON mods');
        $this->addSql('DROP INDEX UNIQ_631EF2FAFF8A180B ON mods');
        $this->addSql('ALTER TABLE mods DROP created_by, DROP last_updated_by');
        $this->addSql('ALTER TABLE permissions DROP FOREIGN KEY FK_2DEDCC6FDE12AB56');
        $this->addSql('ALTER TABLE permissions DROP FOREIGN KEY FK_2DEDCC6FFF8A180B');
        $this->addSql('DROP INDEX UNIQ_2DEDCC6FDE12AB56 ON permissions');
        $this->addSql('DROP INDEX UNIQ_2DEDCC6FFF8A180B ON permissions');
        $this->addSql('ALTER TABLE permissions DROP created_by, DROP last_updated_by');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9DE12AB56');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9FF8A180B');
        $this->addSql('DROP INDEX UNIQ_1483A5E9DE12AB56 ON users');
        $this->addSql('DROP INDEX UNIQ_1483A5E9FF8A180B ON users');
        $this->addSql('DROP INDEX IDX_1483A5E98B8E8428 ON users');
        $this->addSql('DROP INDEX IDX_1483A5E9F85E0677 ON users');
        $this->addSql('ALTER TABLE users ADD password VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP created_by, DROP last_updated_by');
        $this->addSql('ALTER TABLE mod_lists DROP FOREIGN KEY FK_ECB7A267E3C61F9');
        $this->addSql('DROP INDEX IDX_ECB7A267E3C61F9 ON mod_lists');
        $this->addSql('ALTER TABLE mod_lists DROP owner_id');
    }
}
