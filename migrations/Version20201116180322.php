<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201116180322 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemons_abilities DROP FOREIGN KEY FK_AB1E5DBE18777CEF');
        $this->addSql('DROP INDEX IDX_AB1E5DBE18777CEF ON pokemons_abilities');
        $this->addSql('ALTER TABLE pokemons_abilities DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE pokemons_abilities CHANGE talent_id ability_id INT NOT NULL');
        $this->addSql('ALTER TABLE pokemons_abilities ADD CONSTRAINT FK_AB1E5DBE8016D8B2 FOREIGN KEY (ability_id) REFERENCES abilities (id)');
        $this->addSql('CREATE INDEX IDX_AB1E5DBE8016D8B2 ON pokemons_abilities (ability_id)');
        $this->addSql('ALTER TABLE pokemons_abilities ADD PRIMARY KEY (pokemon_id, ability_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemons_abilities DROP FOREIGN KEY FK_AB1E5DBE8016D8B2');
        $this->addSql('DROP INDEX IDX_AB1E5DBE8016D8B2 ON pokemons_abilities');
        $this->addSql('ALTER TABLE pokemons_abilities DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE pokemons_abilities CHANGE ability_id talent_id INT NOT NULL');
        $this->addSql('ALTER TABLE pokemons_abilities ADD CONSTRAINT FK_AB1E5DBE18777CEF FOREIGN KEY (talent_id) REFERENCES abilities (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_AB1E5DBE18777CEF ON pokemons_abilities (talent_id)');
        $this->addSql('ALTER TABLE pokemons_abilities ADD PRIMARY KEY (pokemon_id, talent_id)');
    }
}
