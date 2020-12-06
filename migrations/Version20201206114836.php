<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201206114836 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pokedex_version_group (pokedex_id INT NOT NULL, version_group_id INT NOT NULL, INDEX IDX_66CF0887372A5D14 (pokedex_id), INDEX IDX_66CF088792AE854F (version_group_id), PRIMARY KEY(pokedex_id, version_group_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pokedex_version_group ADD CONSTRAINT FK_66CF0887372A5D14 FOREIGN KEY (pokedex_id) REFERENCES pokedex (id)');
        $this->addSql('ALTER TABLE pokedex_version_group ADD CONSTRAINT FK_66CF088792AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id)');
        $this->addSql('ALTER TABLE pokedex DROP FOREIGN KEY FK_6336F6A792AE854F');
        $this->addSql('DROP INDEX IDX_6336F6A792AE854F ON pokedex');
        $this->addSql('ALTER TABLE pokedex DROP version_group_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pokedex_version_group');
        $this->addSql('ALTER TABLE pokedex ADD version_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokedex ADD CONSTRAINT FK_6336F6A792AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id)');
        $this->addSql('CREATE INDEX IDX_6336F6A792AE854F ON pokedex (version_group_id)');
    }
}
