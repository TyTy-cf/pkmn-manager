<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201129024340 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ability (id INT AUTO_INCREMENT NOT NULL, language_id INT DEFAULT NULL, name VARCHAR(36) DEFAULT NULL, slug VARCHAR(36) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_35CFEE3C82F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE damage_class (id INT AUTO_INCREMENT NOT NULL, language_id INT DEFAULT NULL, image VARCHAR(255) NOT NULL, name VARCHAR(36) DEFAULT NULL, slug VARCHAR(36) DEFAULT NULL, INDEX IDX_7BCB5C8782F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_version (id INT AUTO_INCREMENT NOT NULL, language_id INT DEFAULT NULL, code VARCHAR(2) NOT NULL, name VARCHAR(36) DEFAULT NULL, slug VARCHAR(36) DEFAULT NULL, INDEX IDX_2ADEACEC82F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE genre (id INT AUTO_INCREMENT NOT NULL, language_id INT DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, name VARCHAR(36) DEFAULT NULL, INDEX IDX_835033F882F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(3) NOT NULL, image VARCHAR(255) DEFAULT NULL, name VARCHAR(36) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE move (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, damage_class_id INT DEFAULT NULL, language_id INT DEFAULT NULL, pp INT NOT NULL, accuracy INT DEFAULT NULL, power INT DEFAULT NULL, priority SMALLINT NOT NULL, name VARCHAR(36) DEFAULT NULL, slug VARCHAR(36) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_EF3E3778C54C8C93 (type_id), INDEX IDX_EF3E37789883C12E (damage_class_id), INDEX IDX_EF3E377882F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE move_learn_method (id INT AUTO_INCREMENT NOT NULL, language_id INT DEFAULT NULL, name VARCHAR(36) DEFAULT NULL, slug VARCHAR(36) DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_EF1CDB882F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nature (id INT AUTO_INCREMENT NOT NULL, language_id INT DEFAULT NULL, stat_increased VARCHAR(12) DEFAULT NULL, stat_decreased VARCHAR(12) DEFAULT NULL, name VARCHAR(36) DEFAULT NULL, slug VARCHAR(36) DEFAULT NULL, INDEX IDX_B1D882A782F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon (id INT AUTO_INCREMENT NOT NULL, language_id INT DEFAULT NULL, url_icon VARCHAR(255) DEFAULT NULL, url_sprite_img VARCHAR(255) DEFAULT NULL, hp INT DEFAULT NULL, atk INT DEFAULT NULL, def INT DEFAULT NULL, spa INT DEFAULT NULL, spd INT DEFAULT NULL, spe INT DEFAULT NULL, name VARCHAR(36) DEFAULT NULL, slug VARCHAR(36) DEFAULT NULL, INDEX IDX_62DC90F382F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_abilities (pokemon_id INT NOT NULL, ability_id INT NOT NULL, INDEX IDX_89D167822FE71C3E (pokemon_id), INDEX IDX_89D167828016D8B2 (ability_id), PRIMARY KEY(pokemon_id, ability_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_types (pokemon_id INT NOT NULL, type_id INT NOT NULL, INDEX IDX_B6D930642FE71C3E (pokemon_id), INDEX IDX_B6D93064C54C8C93 (type_id), PRIMARY KEY(pokemon_id, type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_moves_learn_version (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT DEFAULT NULL, move_id INT DEFAULT NULL, move_learn_method_id INT DEFAULT NULL, gameinfos_id INT DEFAULT NULL, level INT NOT NULL, INDEX IDX_7364724F2FE71C3E (pokemon_id), INDEX IDX_7364724F6DC541A8 (move_id), INDEX IDX_7364724F19E2011F (move_learn_method_id), INDEX IDX_7364724FB5C0D298 (gameinfos_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_sheet (id INT AUTO_INCREMENT NOT NULL, name_pokemon INT DEFAULT NULL, gender_id INT DEFAULT NULL, nature_id INT DEFAULT NULL, ability_id INT DEFAULT NULL, ivs_id INT DEFAULT NULL, evs_id INT DEFAULT NULL, stats_id INT DEFAULT NULL, user_id INT DEFAULT NULL, nickname VARCHAR(20) DEFAULT NULL, level INT NOT NULL, UNIQUE INDEX UNIQ_68D528B66AB7D54D (name_pokemon), UNIQUE INDEX UNIQ_68D528B6708A0E0 (gender_id), INDEX IDX_68D528B63BCB2E4B (nature_id), INDEX IDX_68D528B68016D8B2 (ability_id), UNIQUE INDEX UNIQ_68D528B6821F90E (ivs_id), UNIQUE INDEX UNIQ_68D528B67FE33975 (evs_id), UNIQUE INDEX UNIQ_68D528B670AA3482 (stats_id), INDEX IDX_68D528B6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_sheet_moves (pokemon_sheet_id INT NOT NULL, move_id INT NOT NULL, INDEX IDX_A56BA330C76CF0B (pokemon_sheet_id), INDEX IDX_A56BA3306DC541A8 (move_id), PRIMARY KEY(pokemon_sheet_id, move_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stats (id INT AUTO_INCREMENT NOT NULL, hp INT DEFAULT NULL, atk INT DEFAULT NULL, def INT DEFAULT NULL, spa INT DEFAULT NULL, spd INT DEFAULT NULL, spe INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stats_ev (id INT AUTO_INCREMENT NOT NULL, hp INT DEFAULT NULL, atk INT DEFAULT NULL, def INT DEFAULT NULL, spa INT DEFAULT NULL, spd INT DEFAULT NULL, spe INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE stats_iv (id INT AUTO_INCREMENT NOT NULL, hp INT DEFAULT NULL, atk INT DEFAULT NULL, def INT DEFAULT NULL, spa INT DEFAULT NULL, spd INT DEFAULT NULL, spe INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, language_id INT DEFAULT NULL, img VARCHAR(255) DEFAULT NULL, code_api VARCHAR(2) DEFAULT NULL, name VARCHAR(36) DEFAULT NULL, slug VARCHAR(36) DEFAULT NULL, INDEX IDX_8CDE572982F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type_damage_relation_type (id INT AUTO_INCREMENT NOT NULL, type_id INT DEFAULT NULL, damage_relation_type_id INT DEFAULT NULL, damage_relation VARCHAR(4) NOT NULL, damage_relation_coefficient DOUBLE PRECISION NOT NULL, slug VARCHAR(36) DEFAULT NULL, INDEX IDX_8480E846C54C8C93 (type_id), INDEX IDX_8480E8463F2BCAB1 (damage_relation_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, language_id INT DEFAULT NULL, nickname VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, INDEX IDX_8D93D64982F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ability ADD CONSTRAINT FK_35CFEE3C82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE damage_class ADD CONSTRAINT FK_7BCB5C8782F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE game_version ADD CONSTRAINT FK_2ADEACEC82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE genre ADD CONSTRAINT FK_835033F882F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE move ADD CONSTRAINT FK_EF3E3778C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE move ADD CONSTRAINT FK_EF3E37789883C12E FOREIGN KEY (damage_class_id) REFERENCES damage_class (id)');
        $this->addSql('ALTER TABLE move ADD CONSTRAINT FK_EF3E377882F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE move_learn_method ADD CONSTRAINT FK_EF1CDB882F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE nature ADD CONSTRAINT FK_B1D882A782F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F382F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE pokemon_abilities ADD CONSTRAINT FK_89D167822FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_abilities ADD CONSTRAINT FK_89D167828016D8B2 FOREIGN KEY (ability_id) REFERENCES ability (id)');
        $this->addSql('ALTER TABLE pokemon_types ADD CONSTRAINT FK_B6D930642FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_types ADD CONSTRAINT FK_B6D93064C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE pokemon_moves_learn_version ADD CONSTRAINT FK_7364724F2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_moves_learn_version ADD CONSTRAINT FK_7364724F6DC541A8 FOREIGN KEY (move_id) REFERENCES move (id)');
        $this->addSql('ALTER TABLE pokemon_moves_learn_version ADD CONSTRAINT FK_7364724F19E2011F FOREIGN KEY (move_learn_method_id) REFERENCES move_learn_method (id)');
        $this->addSql('ALTER TABLE pokemon_moves_learn_version ADD CONSTRAINT FK_7364724FB5C0D298 FOREIGN KEY (gameinfos_id) REFERENCES game_version (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B66AB7D54D FOREIGN KEY (name_pokemon) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B6708A0E0 FOREIGN KEY (gender_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B63BCB2E4B FOREIGN KEY (nature_id) REFERENCES nature (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B68016D8B2 FOREIGN KEY (ability_id) REFERENCES ability (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B6821F90E FOREIGN KEY (ivs_id) REFERENCES stats_iv (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B67FE33975 FOREIGN KEY (evs_id) REFERENCES stats_ev (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B670AA3482 FOREIGN KEY (stats_id) REFERENCES stats (id)');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pokemon_sheet_moves ADD CONSTRAINT FK_A56BA330C76CF0B FOREIGN KEY (pokemon_sheet_id) REFERENCES pokemon_sheet (id)');
        $this->addSql('ALTER TABLE pokemon_sheet_moves ADD CONSTRAINT FK_A56BA3306DC541A8 FOREIGN KEY (move_id) REFERENCES move (id)');
        $this->addSql('ALTER TABLE type ADD CONSTRAINT FK_8CDE572982F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE type_damage_relation_type ADD CONSTRAINT FK_8480E846C54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE type_damage_relation_type ADD CONSTRAINT FK_8480E8463F2BCAB1 FOREIGN KEY (damage_relation_type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D64982F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_abilities DROP FOREIGN KEY FK_89D167828016D8B2');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B68016D8B2');
        $this->addSql('ALTER TABLE move DROP FOREIGN KEY FK_EF3E37789883C12E');
        $this->addSql('ALTER TABLE pokemon_moves_learn_version DROP FOREIGN KEY FK_7364724FB5C0D298');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B6708A0E0');
        $this->addSql('ALTER TABLE ability DROP FOREIGN KEY FK_35CFEE3C82F1BAF4');
        $this->addSql('ALTER TABLE damage_class DROP FOREIGN KEY FK_7BCB5C8782F1BAF4');
        $this->addSql('ALTER TABLE game_version DROP FOREIGN KEY FK_2ADEACEC82F1BAF4');
        $this->addSql('ALTER TABLE genre DROP FOREIGN KEY FK_835033F882F1BAF4');
        $this->addSql('ALTER TABLE move DROP FOREIGN KEY FK_EF3E377882F1BAF4');
        $this->addSql('ALTER TABLE move_learn_method DROP FOREIGN KEY FK_EF1CDB882F1BAF4');
        $this->addSql('ALTER TABLE nature DROP FOREIGN KEY FK_B1D882A782F1BAF4');
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F382F1BAF4');
        $this->addSql('ALTER TABLE type DROP FOREIGN KEY FK_8CDE572982F1BAF4');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D64982F1BAF4');
        $this->addSql('ALTER TABLE pokemon_moves_learn_version DROP FOREIGN KEY FK_7364724F6DC541A8');
        $this->addSql('ALTER TABLE pokemon_sheet_moves DROP FOREIGN KEY FK_A56BA3306DC541A8');
        $this->addSql('ALTER TABLE pokemon_moves_learn_version DROP FOREIGN KEY FK_7364724F19E2011F');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B63BCB2E4B');
        $this->addSql('ALTER TABLE pokemon_abilities DROP FOREIGN KEY FK_89D167822FE71C3E');
        $this->addSql('ALTER TABLE pokemon_types DROP FOREIGN KEY FK_B6D930642FE71C3E');
        $this->addSql('ALTER TABLE pokemon_moves_learn_version DROP FOREIGN KEY FK_7364724F2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B66AB7D54D');
        $this->addSql('ALTER TABLE pokemon_sheet_moves DROP FOREIGN KEY FK_A56BA330C76CF0B');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B670AA3482');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B67FE33975');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B6821F90E');
        $this->addSql('ALTER TABLE move DROP FOREIGN KEY FK_EF3E3778C54C8C93');
        $this->addSql('ALTER TABLE pokemon_types DROP FOREIGN KEY FK_B6D93064C54C8C93');
        $this->addSql('ALTER TABLE type_damage_relation_type DROP FOREIGN KEY FK_8480E846C54C8C93');
        $this->addSql('ALTER TABLE type_damage_relation_type DROP FOREIGN KEY FK_8480E8463F2BCAB1');
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B6A76ED395');
        $this->addSql('DROP TABLE ability');
        $this->addSql('DROP TABLE damage_class');
        $this->addSql('DROP TABLE game_version');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE move');
        $this->addSql('DROP TABLE move_learn_method');
        $this->addSql('DROP TABLE nature');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE pokemon_abilities');
        $this->addSql('DROP TABLE pokemon_types');
        $this->addSql('DROP TABLE pokemon_moves_learn_version');
        $this->addSql('DROP TABLE pokemon_sheet');
        $this->addSql('DROP TABLE pokemon_sheet_moves');
        $this->addSql('DROP TABLE stats');
        $this->addSql('DROP TABLE stats_ev');
        $this->addSql('DROP TABLE stats_iv');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE type_damage_relation_type');
        $this->addSql('DROP TABLE user');
    }
}