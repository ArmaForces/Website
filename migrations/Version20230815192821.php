<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230815192821 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update table structure for Symfony 6.3 compatibility';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sessions CHANGE sess_data sess_data LONGBLOB NOT NULL');
        $this->addSql('ALTER TABLE sessions RENAME INDEX sessions_sess_lifetime_idx TO sess_lifetime_idx');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sessions CHANGE sess_data sess_data BLOB NOT NULL');
        $this->addSql('ALTER TABLE sessions RENAME INDEX sess_lifetime_idx TO sessions_sess_lifetime_idx');
    }
}
