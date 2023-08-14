<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230814124403 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Remove mod type from directory mod entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mods CHANGE type type VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:mod_type_enum)\'');
        $this->addSql("UPDATE mods SET type = NULL WHERE source = 'directory'");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql("UPDATE mods SET type = 'server_side' WHERE source = 'directory'");
        $this->addSql('ALTER TABLE mods CHANGE type type VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:mod_type_enum)\'');
    }
}
