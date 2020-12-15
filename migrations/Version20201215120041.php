<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201215120041 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_species ADD evolution_chain_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon_species ADD CONSTRAINT FK_C9658B83E417AC09 FOREIGN KEY (evolution_chain_id) REFERENCES evolution_chain (id)');
        $this->addSql('CREATE INDEX IDX_C9658B83E417AC09 ON pokemon_species (evolution_chain_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_species DROP FOREIGN KEY FK_C9658B83E417AC09');
        $this->addSql('DROP INDEX IDX_C9658B83E417AC09 ON pokemon_species');
        $this->addSql('ALTER TABLE pokemon_species DROP evolution_chain_id');
    }
}
