<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201215223218 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evolution_detail ADD trade_species_id INT DEFAULT NULL, ADD turn_upside_down SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE evolution_detail ADD CONSTRAINT FK_16070E5D9C32838D FOREIGN KEY (trade_species_id) REFERENCES pokemon_species (id)');
        $this->addSql('CREATE INDEX IDX_16070E5D9C32838D ON evolution_detail (trade_species_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evolution_detail DROP FOREIGN KEY FK_16070E5D9C32838D');
        $this->addSql('DROP INDEX IDX_16070E5D9C32838D ON evolution_detail');
        $this->addSql('ALTER TABLE evolution_detail DROP trade_species_id, DROP turn_upside_down');
    }
}
