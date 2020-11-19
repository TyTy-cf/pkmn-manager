<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201119183758 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abilities (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) DEFAULT NULL, name_en VARCHAR(24) NOT NULL, name_fr VARCHAR(24) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, name_en VARCHAR(24) NOT NULL, name_fr VARCHAR(24) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evs_pkmn (id INT AUTO_INCREMENT NOT NULL, hp INT NOT NULL, atk INT NOT NULL, def INT NOT NULL, spa INT NOT NULL, spd INT NOT NULL, spe INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, name_en VARCHAR(24) NOT NULL, name_fr VARCHAR(24) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ivs_pkmn (id INT AUTO_INCREMENT NOT NULL, hp INT NOT NULL, atk INT NOT NULL, def INT NOT NULL, spa INT NOT NULL, spd INT NOT NULL, spe INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE moves (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, category_id INT DEFAULT NULL, pp INT NOT NULL, accuracy INT DEFAULT NULL, power INT DEFAULT NULL, name_en VARCHAR(24) NOT NULL, name_fr VARCHAR(24) DEFAULT NULL, INDEX IDX_453F0832C54C8C93 (type_id), INDEX IDX_453F083212469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nature (id INT AUTO_INCREMENT NOT NULL, stats_bonus VARCHAR(12) DEFAULT NULL, stats_penalty VARCHAR(12) DEFAULT NULL, name_en VARCHAR(24) NOT NULL, name_fr VARCHAR(24) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon (id INT AUTO_INCREMENT NOT NULL, url_img VARCHAR(255) DEFAULT NULL, hp INT NOT NULL, atk INT NOT NULL, def INT NOT NULL, spa INT NOT NULL, spd INT NOT NULL, spe INT NOT NULL, name_en VARCHAR(24) NOT NULL, name_fr VARCHAR(24) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemons_abilities (pokemon_id INT NOT NULL, ability_id INT NOT NULL, INDEX IDX_AB1E5DBE2FE71C3E (pokemon_id), INDEX IDX_AB1E5DBE8016D8B2 (ability_id), PRIMARY KEY(pokemon_id, ability_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemons_types (pokemon_id INT NOT NULL, type_id INT NOT NULL, INDEX IDX_B7FC4A102FE71C3E (pokemon_id), INDEX IDX_B7FC4A10C54C8C93 (type_id), PRIMARY KEY(pokemon_id, type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_moves_level (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT DEFAULT NULL, move_id INT DEFAULT NULL, level INT NOT NULL, INDEX IDX_3408D39F2FE71C3E (pokemon_id), INDEX IDX_3408D39F6DC541A8 (move_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_sheet (id INT AUTO_INCREMENT NOT NULL, name_pokemon INT DEFAULT NULL, gender_id INT DEFAULT NULL, nature_id INT DEFAULT NULL, ivs_id INT DEFAULT NULL, evs_id INT DEFAULT NULL, stats_id INT DEFAULT NULL, nickname VARCHAR(20) DEFAULT NULL, level INT NOT NULL, UNIQUE INDEX UNIQ_68D528B66AB7D54D (name_pokemon), UNIQUE INDEX UNIQ_68D528B6708A0E0 (gender_id), UNIQUE INDEX UNIQ_68D528B63BCB2E4B (nature_id), UNIQUE INDEX UNIQ_68D528B6821F90E (ivs_id), UNIQUE INDEX UNIQ_68D528B67FE33975 (evs_id), UNIQUE INDEX UNIQ_68D528B670AA3482 (stats_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_sheet_moves (pokemon_sheet_id INT NOT NULL, move_id INT NOT NULL, INDEX IDX_A56BA330C76CF0B (pokemon_sheet_id), INDEX IDX_A56BA3306DC541A8 (move_id), PRIMARY KEY(pokemon_sheet_id, move_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stats_pkmn (id INT AUTO_INCREMENT NOT NULL, hp INT NOT NULL, atk INT NOT NULL, def INT NOT NULL, spa INT NOT NULL, spd INT NOT NULL, spe INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name_en VARCHAR(24) NOT NULL, name_fr VARCHAR(24) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE moves ADD CONSTRAINT FK_453F0832C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE moves ADD CONSTRAINT FK_453F083212469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE pokemons_abilities ADD CONSTRAINT FK_AB1E5DBE2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemons_abilities ADD CONSTRAINT FK_AB1E5DBE8016D8B2 FOREIGN KEY (ability_id) REFERENCES abilities (id)');
        $this->addSql('ALTER TABLE pokemons_types ADD CONSTRAINT FK_B7FC4A102FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemons_types ADD CONSTRAINT FK_B7FC4A10C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE pokemon_moves_level ADD CONSTRAINT FK_3408D39F2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_moves_level ADD CONSTRAINT FK_3408D39F6DC541A8 FOREIGN KEY (move_id) REFERENCES moves (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B66AB7D54D FOREIGN KEY (name_pokemon) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B6708A0E0 FOREIGN KEY (gender_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B63BCB2E4B FOREIGN KEY (nature_id) REFERENCES nature (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B6821F90E FOREIGN KEY (ivs_id) REFERENCES ivs_pkmn (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B67FE33975 FOREIGN KEY (evs_id) REFERENCES evs_pkmn (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B670AA3482 FOREIGN KEY (stats_id) REFERENCES stats_pkmn (id)');
        $this->addSql('ALTER TABLE pokemon_sheet_moves ADD CONSTRAINT FK_A56BA330C76CF0B FOREIGN KEY (pokemon_sheet_id) REFERENCES pokemon_sheet (id)');
        $this->addSql('ALTER TABLE pokemon_sheet_moves ADD CONSTRAINT FK_A56BA3306DC541A8 FOREIGN KEY (move_id) REFERENCES moves (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemons_abilities DROP FOREIGN KEY FK_AB1E5DBE8016D8B2');
        $this->addSql('ALTER TABLE moves DROP FOREIGN KEY FK_453F083212469DE2');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B67FE33975');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B6708A0E0');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B6821F90E');
        $this->addSql('ALTER TABLE pokemon_moves_level DROP FOREIGN KEY FK_3408D39F6DC541A8');
        $this->addSql('ALTER TABLE pokemon_sheet_moves DROP FOREIGN KEY FK_A56BA3306DC541A8');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B63BCB2E4B');
        $this->addSql('ALTER TABLE pokemons_abilities DROP FOREIGN KEY FK_AB1E5DBE2FE71C3E');
        $this->addSql('ALTER TABLE pokemons_types DROP FOREIGN KEY FK_B7FC4A102FE71C3E');
        $this->addSql('ALTER TABLE pokemon_moves_level DROP FOREIGN KEY FK_3408D39F2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B66AB7D54D');
        $this->addSql('ALTER TABLE pokemon_sheet_moves DROP FOREIGN KEY FK_A56BA330C76CF0B');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B670AA3482');
        $this->addSql('ALTER TABLE moves DROP FOREIGN KEY FK_453F0832C54C8C93');
        $this->addSql('ALTER TABLE pokemons_types DROP FOREIGN KEY FK_B7FC4A10C54C8C93');
        $this->addSql('DROP TABLE abilities');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE evs_pkmn');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE ivs_pkmn');
        $this->addSql('DROP TABLE moves');
        $this->addSql('DROP TABLE nature');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE pokemons_abilities');
        $this->addSql('DROP TABLE pokemons_types');
        $this->addSql('DROP TABLE pokemon_moves_level');
        $this->addSql('DROP TABLE pokemon_sheet');
        $this->addSql('DROP TABLE pokemon_sheet_moves');
        $this->addSql('DROP TABLE stats_pkmn');
        $this->addSql('DROP TABLE type');
    }
}
