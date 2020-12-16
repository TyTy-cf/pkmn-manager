<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201216002603 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evolution_chain_link DROP FOREIGN KEY FK_51633FFAF1095A32');
        $this->addSql('ALTER TABLE evolution_chain_link ADD CONSTRAINT FK_51633FFAF1095A32 FOREIGN KEY (from_pokemon_species_id) REFERENCES pokemon_species (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evolution_chain_link DROP FOREIGN KEY FK_51633FFAF1095A32');
        $this->addSql('ALTER TABLE evolution_chain_link ADD CONSTRAINT FK_51633FFAF1095A32 FOREIGN KEY (from_pokemon_species_id) REFERENCES pokedex_species (id)');
    }
}
