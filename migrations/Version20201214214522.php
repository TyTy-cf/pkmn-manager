<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201214214522 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B66AB7D54D');
        $this->addSql('DROP INDEX UNIQ_68D528B66AB7D54D ON pokemon_sheet');
        $this->addSql('ALTER TABLE pokemon_sheet CHANGE name_pokemon pokemon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B62FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('CREATE INDEX IDX_68D528B62FE71C3E ON pokemon_sheet (pokemon_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B62FE71C3E');
        $this->addSql('DROP INDEX IDX_68D528B62FE71C3E ON pokemon_sheet');
        $this->addSql('ALTER TABLE pokemon_sheet CHANGE pokemon_id name_pokemon INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B66AB7D54D FOREIGN KEY (name_pokemon) REFERENCES pokemon (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_68D528B66AB7D54D ON pokemon_sheet (name_pokemon)');
    }
}
