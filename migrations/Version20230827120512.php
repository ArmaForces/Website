<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230827120512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Replace elao/enum with native PHP enums. Remove Doctrine enum type comments';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mods CHANGE status status VARCHAR(255) DEFAULT NULL, CHANGE type type VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mods CHANGE type type VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:mod_type_enum)\', CHANGE status status VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:mod_status_enum)\'');
    }
}
