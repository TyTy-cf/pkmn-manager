<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201206104307 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_species_version ADD language_id INT DEFAULT NULL, ADD slug VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon_species_version ADD CONSTRAINT FK_20CD1ACC82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_20CD1ACC82F1BAF4 ON pokemon_species_version (language_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_species_version DROP FOREIGN KEY FK_20CD1ACC82F1BAF4');
        $this->addSql('DROP INDEX IDX_20CD1ACC82F1BAF4 ON pokemon_species_version');
        $this->addSql('ALTER TABLE pokemon_species_version DROP language_id, DROP slug');
    }
}
