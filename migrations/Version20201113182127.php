<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201113182127 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abilities (id INT AUTO_INCREMENT NOT NULL, description VARCHAR(255) NOT NULL, name VARCHAR(8) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evs_pkmn (id INT AUTO_INCREMENT NOT NULL, hp INT NOT NULL, atk INT NOT NULL, def INT NOT NULL, spa INT NOT NULL, spd INT NOT NULL, spe INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(8) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ivs_pkmn (id INT AUTO_INCREMENT NOT NULL, hp INT NOT NULL, atk INT NOT NULL, def INT NOT NULL, spa INT NOT NULL, spd INT NOT NULL, spe INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nature (id INT AUTO_INCREMENT NOT NULL, stats_bonus VARCHAR(12) DEFAULT NULL, stats_penalty VARCHAR(12) DEFAULT NULL, name VARCHAR(8) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(120) NOT NULL, hp INT NOT NULL, atk INT NOT NULL, def INT NOT NULL, spa INT NOT NULL, spd INT NOT NULL, spe INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemons_abilities (pokemon_id INT NOT NULL, talent_id INT NOT NULL, INDEX IDX_AB1E5DBE2FE71C3E (pokemon_id), INDEX IDX_AB1E5DBE18777CEF (talent_id), PRIMARY KEY(pokemon_id, talent_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemons_types (pokemon_id INT NOT NULL, type_id INT NOT NULL, INDEX IDX_B7FC4A102FE71C3E (pokemon_id), INDEX IDX_B7FC4A10C54C8C93 (type_id), PRIMARY KEY(pokemon_id, type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_sheet (id INT AUTO_INCREMENT NOT NULL, name_pokemon INT DEFAULT NULL, gender_id INT DEFAULT NULL, nature_id INT DEFAULT NULL, ivs_id INT DEFAULT NULL, evs_id INT DEFAULT NULL, stats_id INT DEFAULT NULL, nickname VARCHAR(20) DEFAULT NULL, level INT NOT NULL, UNIQUE INDEX UNIQ_68D528B66AB7D54D (name_pokemon), UNIQUE INDEX UNIQ_68D528B6708A0E0 (gender_id), UNIQUE INDEX UNIQ_68D528B63BCB2E4B (nature_id), UNIQUE INDEX UNIQ_68D528B6821F90E (ivs_id), UNIQUE INDEX UNIQ_68D528B67FE33975 (evs_id), UNIQUE INDEX UNIQ_68D528B670AA3482 (stats_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stats_pkmn (id INT AUTO_INCREMENT NOT NULL, hp INT NOT NULL, atk INT NOT NULL, def INT NOT NULL, spa INT NOT NULL, spd INT NOT NULL, spe INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(8) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pokemons_abilities ADD CONSTRAINT FK_AB1E5DBE2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemons_abilities ADD CONSTRAINT FK_AB1E5DBE18777CEF FOREIGN KEY (talent_id) REFERENCES abilities (id)');
        $this->addSql('ALTER TABLE pokemons_types ADD CONSTRAINT FK_B7FC4A102FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemons_types ADD CONSTRAINT FK_B7FC4A10C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B66AB7D54D FOREIGN KEY (name_pokemon) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B6708A0E0 FOREIGN KEY (gender_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B63BCB2E4B FOREIGN KEY (nature_id) REFERENCES nature (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B6821F90E FOREIGN KEY (ivs_id) REFERENCES ivs_pkmn (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B67FE33975 FOREIGN KEY (evs_id) REFERENCES evs_pkmn (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B670AA3482 FOREIGN KEY (stats_id) REFERENCES stats_pkmn (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemons_abilities DROP FOREIGN KEY FK_AB1E5DBE18777CEF');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B67FE33975');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B6708A0E0');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B6821F90E');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B63BCB2E4B');
        $this->addSql('ALTER TABLE pokemons_abilities DROP FOREIGN KEY FK_AB1E5DBE2FE71C3E');
        $this->addSql('ALTER TABLE pokemons_types DROP FOREIGN KEY FK_B7FC4A102FE71C3E');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B66AB7D54D');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B670AA3482');
        $this->addSql('ALTER TABLE pokemons_types DROP FOREIGN KEY FK_B7FC4A10C54C8C93');
        $this->addSql('DROP TABLE abilities');
        $this->addSql('DROP TABLE evs_pkmn');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE ivs_pkmn');
        $this->addSql('DROP TABLE nature');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE pokemons_abilities');
        $this->addSql('DROP TABLE pokemons_types');
        $this->addSql('DROP TABLE pokemon_sheet');
        $this->addSql('DROP TABLE stats_pkmn');
        $this->addSql('DROP TABLE type');
    }
}
