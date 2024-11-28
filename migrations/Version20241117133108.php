<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241117133108 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add support for external mod lists';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mod_lists ADD type VARCHAR(255)');
        $this->addSql("UPDATE mod_lists SET type = 'standard'");
        $this->addSql('ALTER TABLE mod_lists ALTER type SET NOT NULL');

        $this->addSql('ALTER TABLE mod_lists ADD url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE mod_lists ALTER approved DROP NOT NULL');

        $this->addSql('ALTER TABLE permissions RENAME COLUMN mod_list_create TO standard_mod_list_create');
        $this->addSql('ALTER TABLE permissions RENAME COLUMN mod_list_update TO standard_mod_list_update');
        $this->addSql('ALTER TABLE permissions RENAME COLUMN mod_list_delete TO standard_mod_list_delete');
        $this->addSql('ALTER TABLE permissions RENAME COLUMN mod_list_copy TO standard_mod_list_copy');
        $this->addSql('ALTER TABLE permissions RENAME COLUMN mod_list_approve TO standard_mod_list_approve');

        $this->addSql('ALTER TABLE permissions ADD external_mod_list_create BOOLEAN');
        $this->addSql('ALTER TABLE permissions ADD external_mod_list_update BOOLEAN');
        $this->addSql('ALTER TABLE permissions ADD external_mod_list_delete BOOLEAN');
        $this->addSql('UPDATE permissions SET external_mod_list_create = false');
        $this->addSql('UPDATE permissions SET external_mod_list_update = false');
        $this->addSql('UPDATE permissions SET external_mod_list_delete = false');
        $this->addSql('ALTER TABLE permissions ALTER external_mod_list_create SET NOT NULL');
        $this->addSql('ALTER TABLE permissions ALTER external_mod_list_update SET NOT NULL');
        $this->addSql('ALTER TABLE permissions ALTER external_mod_list_delete SET NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("DELETE FROM mod_lists WHERE type = 'external'");

        $this->addSql('ALTER TABLE mod_lists DROP type');
        $this->addSql('ALTER TABLE mod_lists DROP url');
        $this->addSql('ALTER TABLE mod_lists ALTER approved SET NOT NULL');

        $this->addSql('ALTER TABLE permissions RENAME COLUMN standard_mod_list_create TO mod_list_create');
        $this->addSql('ALTER TABLE permissions RENAME COLUMN standard_mod_list_update TO mod_list_update');
        $this->addSql('ALTER TABLE permissions RENAME COLUMN standard_mod_list_delete TO mod_list_delete');
        $this->addSql('ALTER TABLE permissions RENAME COLUMN standard_mod_list_copy TO mod_list_copy');
        $this->addSql('ALTER TABLE permissions RENAME COLUMN standard_mod_list_approve TO mod_list_approve');

        $this->addSql('ALTER TABLE permissions DROP external_mod_list_create');
        $this->addSql('ALTER TABLE permissions DROP external_mod_list_update');
        $this->addSql('ALTER TABLE permissions DROP external_mod_list_delete');
    }
}
