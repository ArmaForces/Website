<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210611234047 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add support for DLC in Mod Lists';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dlc (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', created_by CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', last_updated_by CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', last_updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', name VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, app_id BIGINT NOT NULL, UNIQUE INDEX UNIQ_AD6CAEA77987212D (app_id), INDEX IDX_AD6CAEA7DE12AB56 (created_by), INDEX IDX_AD6CAEA7FF8A180B (last_updated_by), INDEX IDX_AD6CAEA78B8E8428 (created_at), INDEX IDX_AD6CAEA7AA163775 (last_updated_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mod_lists_to_dlcs (mod_list_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', dlc_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_73A9D1FEFD60CD19 (mod_list_id), INDEX IDX_73A9D1FECEF6326C (dlc_id), PRIMARY KEY(mod_list_id, dlc_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dlc ADD CONSTRAINT FK_AD6CAEA7DE12AB56 FOREIGN KEY (created_by) REFERENCES users (id)');
        $this->addSql('ALTER TABLE dlc ADD CONSTRAINT FK_AD6CAEA7FF8A180B FOREIGN KEY (last_updated_by) REFERENCES users (id)');
        $this->addSql('ALTER TABLE mod_lists_to_dlcs ADD CONSTRAINT FK_73A9D1FEFD60CD19 FOREIGN KEY (mod_list_id) REFERENCES mod_lists (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE mod_lists_to_dlcs ADD CONSTRAINT FK_73A9D1FECEF6326C FOREIGN KEY (dlc_id) REFERENCES dlc (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE permissions ADD dlc_list TINYINT(1) NOT NULL, ADD dlc_create TINYINT(1) NOT NULL, ADD dlc_update TINYINT(1) NOT NULL, ADD dlc_delete TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE mod_lists_to_dlcs DROP FOREIGN KEY FK_73A9D1FECEF6326C');
        $this->addSql('DROP TABLE dlc');
        $this->addSql('DROP TABLE mod_lists_to_dlcs');
        $this->addSql('ALTER TABLE permissions DROP dlc_list, DROP dlc_create, DROP dlc_update, DROP dlc_delete');
    }
}
