<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231012182336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Init schema';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attendances (id UUID NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, mission_id VARCHAR(255) NOT NULL, player_id BIGINT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9C6B8FD48B8E8428 ON attendances (created_at)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9C6B8FD4BE6CAE9099E6F5DF ON attendances (mission_id, player_id)');
        $this->addSql('COMMENT ON COLUMN attendances.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN attendances.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE dlc (id UUID NOT NULL, created_by UUID DEFAULT NULL, last_updated_by UUID DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, last_updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, app_id BIGINT NOT NULL, directory VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AD6CAEA77987212D ON dlc (app_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AD6CAEA7467844DA ON dlc (directory)');
        $this->addSql('CREATE INDEX IDX_AD6CAEA7DE12AB56 ON dlc (created_by)');
        $this->addSql('CREATE INDEX IDX_AD6CAEA7FF8A180B ON dlc (last_updated_by)');
        $this->addSql('CREATE INDEX IDX_AD6CAEA78B8E8428 ON dlc (created_at)');
        $this->addSql('CREATE INDEX IDX_AD6CAEA7AA163775 ON dlc (last_updated_at)');
        $this->addSql('COMMENT ON COLUMN dlc.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN dlc.created_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN dlc.last_updated_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN dlc.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN dlc.last_updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE mod_groups (id UUID NOT NULL, created_by UUID DEFAULT NULL, last_updated_by UUID DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, last_updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_84DA060FDE12AB56 ON mod_groups (created_by)');
        $this->addSql('CREATE INDEX IDX_84DA060FFF8A180B ON mod_groups (last_updated_by)');
        $this->addSql('CREATE INDEX IDX_84DA060F8B8E8428 ON mod_groups (created_at)');
        $this->addSql('CREATE INDEX IDX_84DA060FAA163775 ON mod_groups (last_updated_at)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_84DA060F5E237E06 ON mod_groups (name)');
        $this->addSql('COMMENT ON COLUMN mod_groups.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN mod_groups.created_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN mod_groups.last_updated_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN mod_groups.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN mod_groups.last_updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE mod_group_to_mods (mod_group_id UUID NOT NULL, mod_id UUID NOT NULL, PRIMARY KEY(mod_group_id, mod_id))');
        $this->addSql('CREATE INDEX IDX_AB46414CE095E5F4 ON mod_group_to_mods (mod_group_id)');
        $this->addSql('CREATE INDEX IDX_AB46414C338E21CD ON mod_group_to_mods (mod_id)');
        $this->addSql('COMMENT ON COLUMN mod_group_to_mods.mod_group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN mod_group_to_mods.mod_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE mod_lists (id UUID NOT NULL, created_by UUID DEFAULT NULL, last_updated_by UUID DEFAULT NULL, owner_id UUID DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, last_updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, active BOOLEAN NOT NULL, approved BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_ECB7A26DE12AB56 ON mod_lists (created_by)');
        $this->addSql('CREATE INDEX IDX_ECB7A26FF8A180B ON mod_lists (last_updated_by)');
        $this->addSql('CREATE INDEX IDX_ECB7A267E3C61F9 ON mod_lists (owner_id)');
        $this->addSql('CREATE INDEX IDX_ECB7A268B8E8428 ON mod_lists (created_at)');
        $this->addSql('CREATE INDEX IDX_ECB7A26AA163775 ON mod_lists (last_updated_at)');
        $this->addSql('CREATE INDEX IDX_ECB7A267C57D81D ON mod_lists (approved)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_ECB7A265E237E06 ON mod_lists (name)');
        $this->addSql('COMMENT ON COLUMN mod_lists.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN mod_lists.created_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN mod_lists.last_updated_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN mod_lists.owner_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN mod_lists.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN mod_lists.last_updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE mod_lists_to_mods (mod_list_id UUID NOT NULL, mod_id UUID NOT NULL, PRIMARY KEY(mod_list_id, mod_id))');
        $this->addSql('CREATE INDEX IDX_43A6B6EAFD60CD19 ON mod_lists_to_mods (mod_list_id)');
        $this->addSql('CREATE INDEX IDX_43A6B6EA338E21CD ON mod_lists_to_mods (mod_id)');
        $this->addSql('COMMENT ON COLUMN mod_lists_to_mods.mod_list_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN mod_lists_to_mods.mod_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE mod_lists_to_mod_groups (mod_list_id UUID NOT NULL, mod_group_id UUID NOT NULL, PRIMARY KEY(mod_list_id, mod_group_id))');
        $this->addSql('CREATE INDEX IDX_CAF4DA88FD60CD19 ON mod_lists_to_mod_groups (mod_list_id)');
        $this->addSql('CREATE INDEX IDX_CAF4DA88E095E5F4 ON mod_lists_to_mod_groups (mod_group_id)');
        $this->addSql('COMMENT ON COLUMN mod_lists_to_mod_groups.mod_list_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN mod_lists_to_mod_groups.mod_group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE mod_lists_to_dlcs (mod_list_id UUID NOT NULL, dlc_id UUID NOT NULL, PRIMARY KEY(mod_list_id, dlc_id))');
        $this->addSql('CREATE INDEX IDX_73A9D1FEFD60CD19 ON mod_lists_to_dlcs (mod_list_id)');
        $this->addSql('CREATE INDEX IDX_73A9D1FECEF6326C ON mod_lists_to_dlcs (dlc_id)');
        $this->addSql('COMMENT ON COLUMN mod_lists_to_dlcs.mod_list_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN mod_lists_to_dlcs.dlc_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE mods (id UUID NOT NULL, created_by UUID DEFAULT NULL, last_updated_by UUID DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, last_updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, status VARCHAR(255) DEFAULT NULL, source VARCHAR(255) NOT NULL, type VARCHAR(255) DEFAULT NULL, item_id BIGINT DEFAULT NULL, directory VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_631EF2FADE12AB56 ON mods (created_by)');
        $this->addSql('CREATE INDEX IDX_631EF2FAFF8A180B ON mods (last_updated_by)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_631EF2FA126F525E ON mods (item_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_631EF2FA467844DA ON mods (directory)');
        $this->addSql('CREATE INDEX IDX_631EF2FA8B8E8428 ON mods (created_at)');
        $this->addSql('CREATE INDEX IDX_631EF2FAAA163775 ON mods (last_updated_at)');
        $this->addSql('COMMENT ON COLUMN mods.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN mods.created_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN mods.last_updated_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN mods.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN mods.last_updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE permissions (id UUID NOT NULL, created_by UUID DEFAULT NULL, last_updated_by UUID DEFAULT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, last_updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, user_list BOOLEAN NOT NULL, user_update BOOLEAN NOT NULL, user_delete BOOLEAN NOT NULL, user_group_list BOOLEAN NOT NULL, user_group_create BOOLEAN NOT NULL, user_group_update BOOLEAN NOT NULL, user_group_delete BOOLEAN NOT NULL, mod_list BOOLEAN NOT NULL, mod_create BOOLEAN NOT NULL, mod_update BOOLEAN NOT NULL, mod_delete BOOLEAN NOT NULL, mod_change_status BOOLEAN NOT NULL, mod_group_list BOOLEAN NOT NULL, mod_group_create BOOLEAN NOT NULL, mod_group_update BOOLEAN NOT NULL, mod_group_delete BOOLEAN NOT NULL, dlc_list BOOLEAN NOT NULL, dlc_create BOOLEAN NOT NULL, dlc_update BOOLEAN NOT NULL, dlc_delete BOOLEAN NOT NULL, mod_list_list BOOLEAN NOT NULL, mod_list_create BOOLEAN NOT NULL, mod_list_update BOOLEAN NOT NULL, mod_list_delete BOOLEAN NOT NULL, mod_list_copy BOOLEAN NOT NULL, mod_list_approve BOOLEAN NOT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2DEDCC6FDE12AB56 ON permissions (created_by)');
        $this->addSql('CREATE INDEX IDX_2DEDCC6FFF8A180B ON permissions (last_updated_by)');
        $this->addSql('CREATE INDEX IDX_2DEDCC6F8B8E8428 ON permissions (created_at)');
        $this->addSql('CREATE INDEX IDX_2DEDCC6FAA163775 ON permissions (last_updated_at)');
        $this->addSql('COMMENT ON COLUMN permissions.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN permissions.created_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN permissions.last_updated_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN permissions.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN permissions.last_updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE user_group (id UUID NOT NULL, created_by UUID DEFAULT NULL, last_updated_by UUID DEFAULT NULL, permissions_id UUID NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, last_updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8F02BF9DDE12AB56 ON user_group (created_by)');
        $this->addSql('CREATE INDEX IDX_8F02BF9DFF8A180B ON user_group (last_updated_by)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8F02BF9D9C3E4F87 ON user_group (permissions_id)');
        $this->addSql('CREATE INDEX IDX_8F02BF9D8B8E8428 ON user_group (created_at)');
        $this->addSql('CREATE INDEX IDX_8F02BF9DAA163775 ON user_group (last_updated_at)');
        $this->addSql('COMMENT ON COLUMN user_group.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_group.created_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_group.last_updated_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_group.permissions_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_group.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN user_group.last_updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE user_groups_to_users (user_group_id UUID NOT NULL, user_id UUID NOT NULL, PRIMARY KEY(user_group_id, user_id))');
        $this->addSql('CREATE INDEX IDX_B67102891ED93D47 ON user_groups_to_users (user_group_id)');
        $this->addSql('CREATE INDEX IDX_B6710289A76ED395 ON user_groups_to_users (user_id)');
        $this->addSql('COMMENT ON COLUMN user_groups_to_users.user_group_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN user_groups_to_users.user_id IS \'(DC2Type:uuid)\'');
        $this->addSql('CREATE TABLE users (id UUID NOT NULL, created_by UUID DEFAULT NULL, last_updated_by UUID DEFAULT NULL, permissions_id UUID NOT NULL, created_at TIMESTAMP(0) WITH TIME ZONE NOT NULL, last_updated_at TIMESTAMP(0) WITH TIME ZONE DEFAULT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, external_id VARCHAR(64) NOT NULL, avatar_hash VARCHAR(512) DEFAULT NULL, steam_id BIGINT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F85E0677 ON users (username)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E99F75D7B0 ON users (external_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9F3FD4ECA ON users (steam_id)');
        $this->addSql('CREATE INDEX IDX_1483A5E9DE12AB56 ON users (created_by)');
        $this->addSql('CREATE INDEX IDX_1483A5E9FF8A180B ON users (last_updated_by)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E99C3E4F87 ON users (permissions_id)');
        $this->addSql('CREATE INDEX IDX_1483A5E98B8E8428 ON users (created_at)');
        $this->addSql('CREATE INDEX IDX_1483A5E9AA163775 ON users (last_updated_at)');
        $this->addSql('COMMENT ON COLUMN users.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.created_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.last_updated_by IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.permissions_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN users.created_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('COMMENT ON COLUMN users.last_updated_at IS \'(DC2Type:datetimetz_immutable)\'');
        $this->addSql('CREATE TABLE sessions (sess_id VARCHAR(128) NOT NULL, sess_data BYTEA NOT NULL, sess_lifetime INT NOT NULL, sess_time INT NOT NULL, PRIMARY KEY(sess_id))');
        $this->addSql('CREATE INDEX sess_lifetime_idx ON sessions (sess_lifetime)');
        $this->addSql('ALTER TABLE dlc ADD CONSTRAINT FK_AD6CAEA7DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dlc ADD CONSTRAINT FK_AD6CAEA7FF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_groups ADD CONSTRAINT FK_84DA060FDE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_groups ADD CONSTRAINT FK_84DA060FFF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_group_to_mods ADD CONSTRAINT FK_AB46414CE095E5F4 FOREIGN KEY (mod_group_id) REFERENCES mod_groups (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_group_to_mods ADD CONSTRAINT FK_AB46414C338E21CD FOREIGN KEY (mod_id) REFERENCES mods (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_lists ADD CONSTRAINT FK_ECB7A26DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_lists ADD CONSTRAINT FK_ECB7A26FF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_lists ADD CONSTRAINT FK_ECB7A267E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_lists_to_mods ADD CONSTRAINT FK_43A6B6EAFD60CD19 FOREIGN KEY (mod_list_id) REFERENCES mod_lists (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_lists_to_mods ADD CONSTRAINT FK_43A6B6EA338E21CD FOREIGN KEY (mod_id) REFERENCES mods (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_lists_to_mod_groups ADD CONSTRAINT FK_CAF4DA88FD60CD19 FOREIGN KEY (mod_list_id) REFERENCES mod_lists (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_lists_to_mod_groups ADD CONSTRAINT FK_CAF4DA88E095E5F4 FOREIGN KEY (mod_group_id) REFERENCES mod_groups (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_lists_to_dlcs ADD CONSTRAINT FK_73A9D1FEFD60CD19 FOREIGN KEY (mod_list_id) REFERENCES mod_lists (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_lists_to_dlcs ADD CONSTRAINT FK_73A9D1FECEF6326C FOREIGN KEY (dlc_id) REFERENCES dlc (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mods ADD CONSTRAINT FK_631EF2FADE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mods ADD CONSTRAINT FK_631EF2FAFF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6FDE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6FFF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DDE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DFF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9D9C3E4F87 FOREIGN KEY (permissions_id) REFERENCES permissions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_groups_to_users ADD CONSTRAINT FK_B67102891ED93D47 FOREIGN KEY (user_group_id) REFERENCES user_group (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_groups_to_users ADD CONSTRAINT FK_B6710289A76ED395 FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9FF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E99C3E4F87 FOREIGN KEY (permissions_id) REFERENCES permissions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dlc DROP CONSTRAINT FK_AD6CAEA7DE12AB56');
        $this->addSql('ALTER TABLE dlc DROP CONSTRAINT FK_AD6CAEA7FF8A180B');
        $this->addSql('ALTER TABLE mod_groups DROP CONSTRAINT FK_84DA060FDE12AB56');
        $this->addSql('ALTER TABLE mod_groups DROP CONSTRAINT FK_84DA060FFF8A180B');
        $this->addSql('ALTER TABLE mod_group_to_mods DROP CONSTRAINT FK_AB46414CE095E5F4');
        $this->addSql('ALTER TABLE mod_group_to_mods DROP CONSTRAINT FK_AB46414C338E21CD');
        $this->addSql('ALTER TABLE mod_lists DROP CONSTRAINT FK_ECB7A26DE12AB56');
        $this->addSql('ALTER TABLE mod_lists DROP CONSTRAINT FK_ECB7A26FF8A180B');
        $this->addSql('ALTER TABLE mod_lists DROP CONSTRAINT FK_ECB7A267E3C61F9');
        $this->addSql('ALTER TABLE mod_lists_to_mods DROP CONSTRAINT FK_43A6B6EAFD60CD19');
        $this->addSql('ALTER TABLE mod_lists_to_mods DROP CONSTRAINT FK_43A6B6EA338E21CD');
        $this->addSql('ALTER TABLE mod_lists_to_mod_groups DROP CONSTRAINT FK_CAF4DA88FD60CD19');
        $this->addSql('ALTER TABLE mod_lists_to_mod_groups DROP CONSTRAINT FK_CAF4DA88E095E5F4');
        $this->addSql('ALTER TABLE mod_lists_to_dlcs DROP CONSTRAINT FK_73A9D1FEFD60CD19');
        $this->addSql('ALTER TABLE mod_lists_to_dlcs DROP CONSTRAINT FK_73A9D1FECEF6326C');
        $this->addSql('ALTER TABLE mods DROP CONSTRAINT FK_631EF2FADE12AB56');
        $this->addSql('ALTER TABLE mods DROP CONSTRAINT FK_631EF2FAFF8A180B');
        $this->addSql('ALTER TABLE permissions DROP CONSTRAINT FK_2DEDCC6FDE12AB56');
        $this->addSql('ALTER TABLE permissions DROP CONSTRAINT FK_2DEDCC6FFF8A180B');
        $this->addSql('ALTER TABLE user_group DROP CONSTRAINT FK_8F02BF9DDE12AB56');
        $this->addSql('ALTER TABLE user_group DROP CONSTRAINT FK_8F02BF9DFF8A180B');
        $this->addSql('ALTER TABLE user_group DROP CONSTRAINT FK_8F02BF9D9C3E4F87');
        $this->addSql('ALTER TABLE user_groups_to_users DROP CONSTRAINT FK_B67102891ED93D47');
        $this->addSql('ALTER TABLE user_groups_to_users DROP CONSTRAINT FK_B6710289A76ED395');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9DE12AB56');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9FF8A180B');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E99C3E4F87');
        $this->addSql('DROP TABLE attendances');
        $this->addSql('DROP TABLE dlc');
        $this->addSql('DROP TABLE mod_groups');
        $this->addSql('DROP TABLE mod_group_to_mods');
        $this->addSql('DROP TABLE mod_lists');
        $this->addSql('DROP TABLE mod_lists_to_mods');
        $this->addSql('DROP TABLE mod_lists_to_mod_groups');
        $this->addSql('DROP TABLE mod_lists_to_dlcs');
        $this->addSql('DROP TABLE mods');
        $this->addSql('DROP TABLE permissions');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP TABLE user_groups_to_users');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE sessions');
    }
}
