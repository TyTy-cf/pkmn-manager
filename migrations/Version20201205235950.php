<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201205235950 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon ADD pokemon_species_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3609DB477 FOREIGN KEY (pokemon_species_id) REFERENCES pokemon_species (id)');
        $this->addSql('CREATE INDEX IDX_62DC90F3609DB477 ON pokemon (pokemon_species_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3609DB477');
        $this->addSql('DROP INDEX IDX_62DC90F3609DB477 ON pokemon');
        $this->addSql('ALTER TABLE pokemon DROP pokemon_species_id');
    }
}
