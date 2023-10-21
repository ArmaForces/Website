<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231021222125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Switch from native enums to doctrine enums';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mods ALTER status TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE mods ALTER type TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE mods ALTER type SET NOT NULL');
        $this->addSql('COMMENT ON COLUMN mods.status IS \'(DC2Type:mod_status_enum)\'');
        $this->addSql('COMMENT ON COLUMN mods.type IS \'(DC2Type:mod_type_enum)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mods ALTER type TYPE VARCHAR(255)');
        $this->addSql('ALTER TABLE mods ALTER type DROP NOT NULL');
        $this->addSql('ALTER TABLE mods ALTER status TYPE VARCHAR(255)');
        $this->addSql('COMMENT ON COLUMN mods.type IS NULL');
        $this->addSql('COMMENT ON COLUMN mods.status IS NULL');
    }
}
