<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201206124942 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokedex_species DROP FOREIGN KEY FK_7AD01E34609DB477');
        $this->addSql('ALTER TABLE pokedex_species ADD CONSTRAINT FK_7AD01E34609DB477 FOREIGN KEY (pokemon_species_id) REFERENCES pokemon_species (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokedex_species DROP FOREIGN KEY FK_7AD01E34609DB477');
        $this->addSql('ALTER TABLE pokedex_species ADD CONSTRAINT FK_7AD01E34609DB477 FOREIGN KEY (pokemon_species_id) REFERENCES pokedex_species (id)');
    }
}
