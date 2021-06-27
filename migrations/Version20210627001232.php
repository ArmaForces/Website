<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210627001232 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Use native database functionality for cascade remove operations';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mod_group_to_mods DROP FOREIGN KEY FK_AAC336B7338E21CD');
        $this->addSql('ALTER TABLE mod_group_to_mods DROP FOREIGN KEY FK_AAC336B7E095E5F4');
        $this->addSql('ALTER TABLE mod_group_to_mods ADD CONSTRAINT FK_AB46414CE095E5F4 FOREIGN KEY (mod_group_id) REFERENCES mod_groups (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mod_group_to_mods ADD CONSTRAINT FK_AB46414C338E21CD FOREIGN KEY (mod_id) REFERENCES mods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mod_lists_to_mods DROP FOREIGN KEY FK_77414C92338E21CD');
        $this->addSql('ALTER TABLE mod_lists_to_mods DROP FOREIGN KEY FK_77414C92FD60CD19');
        $this->addSql('ALTER TABLE mod_lists_to_mods ADD CONSTRAINT FK_43A6B6EAFD60CD19 FOREIGN KEY (mod_list_id) REFERENCES mod_lists (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mod_lists_to_mods ADD CONSTRAINT FK_43A6B6EA338E21CD FOREIGN KEY (mod_id) REFERENCES mods (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mod_lists_to_mod_groups DROP FOREIGN KEY FK_47CB2915E095E5F4');
        $this->addSql('ALTER TABLE mod_lists_to_mod_groups DROP FOREIGN KEY FK_47CB2915FD60CD19');
        $this->addSql('ALTER TABLE mod_lists_to_mod_groups ADD CONSTRAINT FK_CAF4DA88FD60CD19 FOREIGN KEY (mod_list_id) REFERENCES mod_lists (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mod_lists_to_mod_groups ADD CONSTRAINT FK_CAF4DA88E095E5F4 FOREIGN KEY (mod_group_id) REFERENCES mod_groups (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mod_group_to_mods DROP FOREIGN KEY FK_AB46414CE095E5F4');
        $this->addSql('ALTER TABLE mod_group_to_mods DROP FOREIGN KEY FK_AB46414C338E21CD');
        $this->addSql('ALTER TABLE mod_group_to_mods ADD CONSTRAINT FK_AAC336B7338E21CD FOREIGN KEY (mod_id) REFERENCES mods (id)');
        $this->addSql('ALTER TABLE mod_group_to_mods ADD CONSTRAINT FK_AAC336B7E095E5F4 FOREIGN KEY (mod_group_id) REFERENCES mod_groups (id)');
        $this->addSql('ALTER TABLE mod_lists_to_mod_groups DROP FOREIGN KEY FK_CAF4DA88FD60CD19');
        $this->addSql('ALTER TABLE mod_lists_to_mod_groups DROP FOREIGN KEY FK_CAF4DA88E095E5F4');
        $this->addSql('ALTER TABLE mod_lists_to_mod_groups ADD CONSTRAINT FK_47CB2915E095E5F4 FOREIGN KEY (mod_group_id) REFERENCES mod_groups (id)');
        $this->addSql('ALTER TABLE mod_lists_to_mod_groups ADD CONSTRAINT FK_47CB2915FD60CD19 FOREIGN KEY (mod_list_id) REFERENCES mod_lists (id)');
        $this->addSql('ALTER TABLE mod_lists_to_mods DROP FOREIGN KEY FK_43A6B6EAFD60CD19');
        $this->addSql('ALTER TABLE mod_lists_to_mods DROP FOREIGN KEY FK_43A6B6EA338E21CD');
        $this->addSql('ALTER TABLE mod_lists_to_mods ADD CONSTRAINT FK_77414C92338E21CD FOREIGN KEY (mod_id) REFERENCES mods (id)');
        $this->addSql('ALTER TABLE mod_lists_to_mods ADD CONSTRAINT FK_77414C92FD60CD19 FOREIGN KEY (mod_list_id) REFERENCES mod_lists (id)');
    }
}
