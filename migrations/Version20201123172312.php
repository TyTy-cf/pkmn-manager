<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201123172312 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_infos (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(6) NOT NULL, nb_pkmn INT DEFAULT NULL, name_en VARCHAR(24) NOT NULL, name_fr VARCHAR(24) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pokemon_moves_level ADD gameinfos_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon_moves_level ADD CONSTRAINT FK_3408D39FB5C0D298 FOREIGN KEY (gameinfos_id) REFERENCES game_infos (id)');
        $this->addSql('CREATE INDEX IDX_3408D39FB5C0D298 ON pokemon_moves_level (gameinfos_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_moves_level DROP FOREIGN KEY FK_3408D39FB5C0D298');
        $this->addSql('DROP TABLE game_infos');
        $this->addSql('DROP INDEX IDX_3408D39FB5C0D298 ON pokemon_moves_level');
        $this->addSql('ALTER TABLE pokemon_moves_level DROP gameinfos_id');
    }
}
