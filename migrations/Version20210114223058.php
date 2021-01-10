<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210114223058 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Mod status';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE permissions ADD mod_change_status TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE mods ADD status VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:mod_status_enum)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf('mysql' !== $this->connection->getDatabasePlatform()->getName(), 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE mods DROP status');
        $this->addSql('ALTER TABLE permissions DROP mod_change_status');
    }
}
