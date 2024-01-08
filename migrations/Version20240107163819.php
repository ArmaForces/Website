<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240107163819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Fix cascade delete for user association';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dlc DROP CONSTRAINT FK_AD6CAEA7DE12AB56');
        $this->addSql('ALTER TABLE dlc DROP CONSTRAINT FK_AD6CAEA7FF8A180B');
        $this->addSql('ALTER TABLE dlc ADD CONSTRAINT FK_AD6CAEA7DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dlc ADD CONSTRAINT FK_AD6CAEA7FF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_groups DROP CONSTRAINT FK_84DA060FDE12AB56');
        $this->addSql('ALTER TABLE mod_groups DROP CONSTRAINT FK_84DA060FFF8A180B');
        $this->addSql('ALTER TABLE mod_groups ADD CONSTRAINT FK_84DA060FDE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_groups ADD CONSTRAINT FK_84DA060FFF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_lists DROP CONSTRAINT FK_ECB7A26DE12AB56');
        $this->addSql('ALTER TABLE mod_lists DROP CONSTRAINT FK_ECB7A26FF8A180B');
        $this->addSql('ALTER TABLE mod_lists DROP CONSTRAINT FK_ECB7A267E3C61F9');
        $this->addSql('ALTER TABLE mod_lists ADD CONSTRAINT FK_ECB7A26DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_lists ADD CONSTRAINT FK_ECB7A26FF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_lists ADD CONSTRAINT FK_ECB7A267E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mods DROP CONSTRAINT FK_631EF2FADE12AB56');
        $this->addSql('ALTER TABLE mods DROP CONSTRAINT FK_631EF2FAFF8A180B');
        $this->addSql('ALTER TABLE mods ADD CONSTRAINT FK_631EF2FADE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mods ADD CONSTRAINT FK_631EF2FAFF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE permissions DROP CONSTRAINT FK_2DEDCC6FDE12AB56');
        $this->addSql('ALTER TABLE permissions DROP CONSTRAINT FK_2DEDCC6FFF8A180B');
        $this->addSql('ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6FDE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE permissions ADD CONSTRAINT FK_2DEDCC6FFF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_group DROP CONSTRAINT FK_8F02BF9DDE12AB56');
        $this->addSql('ALTER TABLE user_group DROP CONSTRAINT FK_8F02BF9DFF8A180B');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DDE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DFF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9DE12AB56');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT FK_1483A5E9FF8A180B');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9FF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mods DROP CONSTRAINT fk_631ef2fade12ab56');
        $this->addSql('ALTER TABLE mods DROP CONSTRAINT fk_631ef2faff8a180b');
        $this->addSql('ALTER TABLE mods ADD CONSTRAINT fk_631ef2fade12ab56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mods ADD CONSTRAINT fk_631ef2faff8a180b FOREIGN KEY (last_updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_groups DROP CONSTRAINT fk_84da060fde12ab56');
        $this->addSql('ALTER TABLE mod_groups DROP CONSTRAINT fk_84da060fff8a180b');
        $this->addSql('ALTER TABLE mod_groups ADD CONSTRAINT fk_84da060fde12ab56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_groups ADD CONSTRAINT fk_84da060fff8a180b FOREIGN KEY (last_updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT fk_1483a5e9de12ab56');
        $this->addSql('ALTER TABLE users DROP CONSTRAINT fk_1483a5e9ff8a180b');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT fk_1483a5e9de12ab56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT fk_1483a5e9ff8a180b FOREIGN KEY (last_updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_group DROP CONSTRAINT fk_8f02bf9dde12ab56');
        $this->addSql('ALTER TABLE user_group DROP CONSTRAINT fk_8f02bf9dff8a180b');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT fk_8f02bf9dde12ab56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT fk_8f02bf9dff8a180b FOREIGN KEY (last_updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE permissions DROP CONSTRAINT fk_2dedcc6fde12ab56');
        $this->addSql('ALTER TABLE permissions DROP CONSTRAINT fk_2dedcc6fff8a180b');
        $this->addSql('ALTER TABLE permissions ADD CONSTRAINT fk_2dedcc6fde12ab56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE permissions ADD CONSTRAINT fk_2dedcc6fff8a180b FOREIGN KEY (last_updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dlc DROP CONSTRAINT fk_ad6caea7de12ab56');
        $this->addSql('ALTER TABLE dlc DROP CONSTRAINT fk_ad6caea7ff8a180b');
        $this->addSql('ALTER TABLE dlc ADD CONSTRAINT fk_ad6caea7de12ab56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dlc ADD CONSTRAINT fk_ad6caea7ff8a180b FOREIGN KEY (last_updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_lists DROP CONSTRAINT fk_ecb7a26de12ab56');
        $this->addSql('ALTER TABLE mod_lists DROP CONSTRAINT fk_ecb7a26ff8a180b');
        $this->addSql('ALTER TABLE mod_lists DROP CONSTRAINT fk_ecb7a267e3c61f9');
        $this->addSql('ALTER TABLE mod_lists ADD CONSTRAINT fk_ecb7a26de12ab56 FOREIGN KEY (created_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_lists ADD CONSTRAINT fk_ecb7a26ff8a180b FOREIGN KEY (last_updated_by) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE mod_lists ADD CONSTRAINT fk_ecb7a267e3c61f9 FOREIGN KEY (owner_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }
}
