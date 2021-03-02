<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210302175416 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ability_version_group ADD version_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ability_version_group ADD CONSTRAINT FK_6B1D56B18016D8B2 FOREIGN KEY (ability_id) REFERENCES ability (id)');
        $this->addSql('ALTER TABLE ability_version_group ADD CONSTRAINT FK_6B1D56B192AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id)');
        $this->addSql('CREATE INDEX IDX_6B1D56B192AE854F ON ability_version_group (version_group_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ability_version_group DROP FOREIGN KEY FK_6B1D56B18016D8B2');
        $this->addSql('ALTER TABLE ability_version_group DROP FOREIGN KEY FK_6B1D56B192AE854F');
        $this->addSql('DROP INDEX IDX_6B1D56B192AE854F ON ability_version_group');
        $this->addSql('ALTER TABLE ability_version_group DROP version_group_id');
    }
}
