<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210625211018 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add directory to DLC entity';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dlc ADD directory VARCHAR(255)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AD6CAEA7467844DA ON dlc (directory)');

        // Update existing DLCs
        $this->addSql("UPDATE dlc SET directory = 'vn' WHERE app_id = 1227700");
        $this->addSql("UPDATE dlc SET directory = 'csla' WHERE app_id = 1294440");
        $this->addSql("UPDATE dlc SET directory = 'gm' WHERE app_id = 1042220");

        $this->addSql('ALTER TABLE dlc MODIFY directory VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_AD6CAEA7467844DA ON dlc');
        $this->addSql('ALTER TABLE dlc DROP directory');
    }
}
