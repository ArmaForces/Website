<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210131115809 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add User Groups';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('RENAME TABLE mod_group_mods TO mod_group_to_mods;');
        $this->addSql('RENAME TABLE mod_lists_mod_groups TO mod_lists_to_mod_groups;');
        $this->addSql('RENAME TABLE mod_lists_mods TO mod_lists_to_mods;');

        $this->addSql('ALTER TABLE mod_group_to_mods RENAME INDEX idx_aac336b7e095e5f4 TO IDX_AB46414CE095E5F4');
        $this->addSql('ALTER TABLE mod_group_to_mods RENAME INDEX idx_aac336b7338e21cd TO IDX_AB46414C338E21CD');
        $this->addSql('ALTER TABLE mod_lists_to_mods RENAME INDEX idx_77414c92fd60cd19 TO IDX_43A6B6EAFD60CD19');
        $this->addSql('ALTER TABLE mod_lists_to_mods RENAME INDEX idx_77414c92338e21cd TO IDX_43A6B6EA338E21CD');
        $this->addSql('ALTER TABLE mod_lists_to_mod_groups RENAME INDEX idx_47cb2915fd60cd19 TO IDX_CAF4DA88FD60CD19');
        $this->addSql('ALTER TABLE mod_lists_to_mod_groups RENAME INDEX idx_47cb2915e095e5f4 TO IDX_CAF4DA88E095E5F4');

        $this->addSql('CREATE TABLE user_group (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_by CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', last_updated_by CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', permissions_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_8F02BF9DDE12AB56 (created_by), INDEX IDX_8F02BF9DFF8A180B (last_updated_by), UNIQUE INDEX UNIQ_8F02BF9D9C3E4F87 (permissions_id), INDEX IDX_8F02BF9D8B8E8428 (created_at), INDEX IDX_8F02BF9DAA163775 (last_updated_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_groups_to_users (user_group_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_B67102891ED93D47 (user_group_id), INDEX IDX_B6710289A76ED395 (user_id), PRIMARY KEY(user_group_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DDE12AB56 FOREIGN KEY (created_by) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9DFF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id)');
        $this->addSql('ALTER TABLE user_group ADD CONSTRAINT FK_8F02BF9D9C3E4F87 FOREIGN KEY (permissions_id) REFERENCES permissions (id)');
        $this->addSql('ALTER TABLE user_groups_to_users ADD CONSTRAINT FK_B67102891ED93D47 FOREIGN KEY (user_group_id) REFERENCES user_group (id)');
        $this->addSql('ALTER TABLE user_groups_to_users ADD CONSTRAINT FK_B6710289A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE permissions ADD user_group_list TINYINT(1) NOT NULL, ADD user_group_create TINYINT(1) NOT NULL, ADD user_group_update TINYINT(1) NOT NULL, ADD user_group_delete TINYINT(1) NOT NULL, ADD type VARCHAR(255) NOT NULL');

        $this->addSql("UPDATE permissions SET type = 'user'");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('RENAME TABLE mod_group_to_mods TO mod_group_mods;');
        $this->addSql('RENAME TABLE mod_lists_to_mod_groups TO mod_lists_mod_groups;');
        $this->addSql('RENAME TABLE mod_lists_to_mods TO mod_lists_mods;');

        $this->addSql('ALTER TABLE mod_group_mods RENAME INDEX idx_ab46414ce095e5f4 TO IDX_AAC336B7E095E5F4');
        $this->addSql('ALTER TABLE mod_group_mods RENAME INDEX idx_ab46414c338e21cd TO IDX_AAC336B7338E21CD');
        $this->addSql('ALTER TABLE mod_lists_mod_groups RENAME INDEX idx_caf4da88fd60cd19 TO IDX_47CB2915FD60CD19');
        $this->addSql('ALTER TABLE mod_lists_mod_groups RENAME INDEX idx_caf4da88e095e5f4 TO IDX_47CB2915E095E5F4');
        $this->addSql('ALTER TABLE mod_lists_mods RENAME INDEX idx_43a6b6eafd60cd19 TO IDX_77414C92FD60CD19');
        $this->addSql('ALTER TABLE mod_lists_mods RENAME INDEX idx_43a6b6ea338e21cd TO IDX_77414C92338E21CD');

        $this->addSql('ALTER TABLE user_groups_to_users DROP FOREIGN KEY FK_B67102891ED93D47');
        $this->addSql('DROP TABLE user_group');
        $this->addSql('DROP TABLE user_groups_to_users');
        $this->addSql('ALTER TABLE permissions DROP user_group_list, DROP user_group_create, DROP user_group_update, DROP user_group_delete, DROP type');
    }
}
