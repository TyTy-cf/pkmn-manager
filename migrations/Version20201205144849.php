<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201205144849 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evolution_chain_evolution_chain (evolution_chain_source INT NOT NULL, evolution_chain_target INT NOT NULL, INDEX IDX_52868A8183CBC7AA (evolution_chain_source), INDEX IDX_52868A819A2E9725 (evolution_chain_target), PRIMARY KEY(evolution_chain_source, evolution_chain_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, held_by_pokemon_id INT DEFAULT NULL, language_id INT DEFAULT NULL, fling_power INT DEFAULT NULL, fling_effect VARCHAR(255) DEFAULT NULL, cost INT DEFAULT NULL, sprite_url VARCHAR(255) DEFAULT NULL, name VARCHAR(36) NOT NULL, slug VARCHAR(255) NOT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_1F1B251EC7139AED (held_by_pokemon_id), INDEX IDX_1F1B251E82F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evolution_chain_evolution_chain ADD CONSTRAINT FK_52868A8183CBC7AA FOREIGN KEY (evolution_chain_source) REFERENCES evolution_chain (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evolution_chain_evolution_chain ADD CONSTRAINT FK_52868A819A2E9725 FOREIGN KEY (evolution_chain_target) REFERENCES evolution_chain (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EC7139AED FOREIGN KEY (held_by_pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251E82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE evolution_chain ADD evolve_to_pokemon_species_id INT DEFAULT NULL, ADD current_pokemon_species_id INT DEFAULT NULL, ADD evolution_detail_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evolution_chain ADD CONSTRAINT FK_68B112687D66DE33 FOREIGN KEY (evolve_to_pokemon_species_id) REFERENCES pokedex_species (id)');
        $this->addSql('ALTER TABLE evolution_chain ADD CONSTRAINT FK_68B11268ACC5F3FC FOREIGN KEY (current_pokemon_species_id) REFERENCES pokedex_species (id)');
        $this->addSql('ALTER TABLE evolution_chain ADD CONSTRAINT FK_68B112682C2C0E8 FOREIGN KEY (evolution_detail_id) REFERENCES evolution_detail (id)');
        $this->addSql('CREATE INDEX IDX_68B112687D66DE33 ON evolution_chain (evolve_to_pokemon_species_id)');
        $this->addSql('CREATE INDEX IDX_68B11268ACC5F3FC ON evolution_chain (current_pokemon_species_id)');
        $this->addSql('CREATE INDEX IDX_68B112682C2C0E8 ON evolution_chain (evolution_detail_id)');
        $this->addSql('ALTER TABLE evolution_detail ADD gender_id INT DEFAULT NULL, ADD held_item_id INT DEFAULT NULL, ADD used_item_id INT DEFAULT NULL, ADD known_move_id INT DEFAULT NULL, ADD known_move_type_id INT DEFAULT NULL, ADD pokemon_species_id INT DEFAULT NULL, ADD party_type_id INT DEFAULT NULL, ADD evolution_trigger_id INT DEFAULT NULL, ADD location VARCHAR(255) DEFAULT NULL, ADD min_affection VARCHAR(255) DEFAULT NULL, ADD min_beauty VARCHAR(255) DEFAULT NULL, ADD min_happiness VARCHAR(255) DEFAULT NULL, ADD min_level VARCHAR(255) DEFAULT NULL, ADD needs_overworld_rain VARCHAR(255) DEFAULT NULL, ADD time_of_day VARCHAR(255) DEFAULT NULL, ADD relative_physical_stats VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE evolution_detail ADD CONSTRAINT FK_16070E5D708A0E0 FOREIGN KEY (gender_id) REFERENCES genre (id)');
        $this->addSql('ALTER TABLE evolution_detail ADD CONSTRAINT FK_16070E5DBD59BEFF FOREIGN KEY (held_item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE evolution_detail ADD CONSTRAINT FK_16070E5D95B877AF FOREIGN KEY (used_item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE evolution_detail ADD CONSTRAINT FK_16070E5D189A45FC FOREIGN KEY (known_move_id) REFERENCES move (id)');
        $this->addSql('ALTER TABLE evolution_detail ADD CONSTRAINT FK_16070E5D40D850A6 FOREIGN KEY (known_move_type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE evolution_detail ADD CONSTRAINT FK_16070E5D609DB477 FOREIGN KEY (pokemon_species_id) REFERENCES pokemon_species (id)');
        $this->addSql('ALTER TABLE evolution_detail ADD CONSTRAINT FK_16070E5DD7BD13B8 FOREIGN KEY (party_type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE evolution_detail ADD CONSTRAINT FK_16070E5DAD65CE5B FOREIGN KEY (evolution_trigger_id) REFERENCES evolution_trigger (id)');
        $this->addSql('CREATE INDEX IDX_16070E5D708A0E0 ON evolution_detail (gender_id)');
        $this->addSql('CREATE INDEX IDX_16070E5DBD59BEFF ON evolution_detail (held_item_id)');
        $this->addSql('CREATE INDEX IDX_16070E5D95B877AF ON evolution_detail (used_item_id)');
        $this->addSql('CREATE INDEX IDX_16070E5D189A45FC ON evolution_detail (known_move_id)');
        $this->addSql('CREATE INDEX IDX_16070E5D40D850A6 ON evolution_detail (known_move_type_id)');
        $this->addSql('CREATE INDEX IDX_16070E5D609DB477 ON evolution_detail (pokemon_species_id)');
        $this->addSql('CREATE INDEX IDX_16070E5DD7BD13B8 ON evolution_detail (party_type_id)');
        $this->addSql('CREATE INDEX IDX_16070E5DAD65CE5B ON evolution_detail (evolution_trigger_id)');
        $this->addSql('ALTER TABLE pokedex ADD version_group_id INT DEFAULT NULL, ADD language_id INT DEFAULT NULL, ADD name VARCHAR(36) NOT NULL, ADD slug VARCHAR(255) NOT NULL, ADD description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE pokedex ADD CONSTRAINT FK_6336F6A792AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id)');
        $this->addSql('ALTER TABLE pokedex ADD CONSTRAINT FK_6336F6A782F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_6336F6A792AE854F ON pokedex (version_group_id)');
        $this->addSql('CREATE INDEX IDX_6336F6A782F1BAF4 ON pokedex (language_id)');
        $this->addSql('ALTER TABLE pokedex_species ADD pokemon_species_id INT DEFAULT NULL, ADD pokedex_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokedex_species ADD CONSTRAINT FK_7AD01E34609DB477 FOREIGN KEY (pokemon_species_id) REFERENCES pokedex_species (id)');
        $this->addSql('ALTER TABLE pokedex_species ADD CONSTRAINT FK_7AD01E34372A5D14 FOREIGN KEY (pokedex_id) REFERENCES pokedex (id)');
        $this->addSql('CREATE INDEX IDX_7AD01E34609DB477 ON pokedex_species (pokemon_species_id)');
        $this->addSql('CREATE INDEX IDX_7AD01E34372A5D14 ON pokedex_species (pokedex_id)');
        $this->addSql('ALTER TABLE pokemon_species ADD egg_group_id INT DEFAULT NULL, ADD evolves_from_species_id INT DEFAULT NULL, ADD capture_rate INT DEFAULT NULL, ADD growth_rate INT DEFAULT NULL, ADD hatch_counter INT DEFAULT NULL, ADD is_legendary SMALLINT DEFAULT NULL, ADD has_gender_differences SMALLINT DEFAULT NULL, ADD is_mythical SMALLINT DEFAULT NULL, ADD is_baby SMALLINT DEFAULT NULL, CHANGE base_happiness base_happiness INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon_species ADD CONSTRAINT FK_C9658B83B76DC94C FOREIGN KEY (egg_group_id) REFERENCES egg_group (id)');
        $this->addSql('ALTER TABLE pokemon_species ADD CONSTRAINT FK_C9658B837D11D524 FOREIGN KEY (evolves_from_species_id) REFERENCES pokemon_species (id)');
        $this->addSql('CREATE INDEX IDX_C9658B83B76DC94C ON pokemon_species (egg_group_id)');
        $this->addSql('CREATE INDEX IDX_C9658B837D11D524 ON pokemon_species (evolves_from_species_id)');
        $this->addSql('ALTER TABLE pokemon_species_version ADD pokemon_species_id INT DEFAULT NULL, ADD version_id INT DEFAULT NULL, CHANGE flavor_text description VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon_species_version ADD CONSTRAINT FK_20CD1ACC609DB477 FOREIGN KEY (pokemon_species_id) REFERENCES pokemon_species (id)');
        $this->addSql('ALTER TABLE pokemon_species_version ADD CONSTRAINT FK_20CD1ACC4BBC2705 FOREIGN KEY (version_id) REFERENCES version (id)');
        $this->addSql('CREATE INDEX IDX_20CD1ACC609DB477 ON pokemon_species_version (pokemon_species_id)');
        $this->addSql('CREATE INDEX IDX_20CD1ACC4BBC2705 ON pokemon_species_version (version_id)');
        $this->addSql('ALTER TABLE species_evolution ADD pokemon_species_id INT DEFAULT NULL, ADD evolution_chain_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE species_evolution ADD CONSTRAINT FK_12EF986D609DB477 FOREIGN KEY (pokemon_species_id) REFERENCES pokedex_species (id)');
        $this->addSql('ALTER TABLE species_evolution ADD CONSTRAINT FK_12EF986DE417AC09 FOREIGN KEY (evolution_chain_id) REFERENCES evolution_chain (id)');
        $this->addSql('CREATE INDEX IDX_12EF986D609DB477 ON species_evolution (pokemon_species_id)');
        $this->addSql('CREATE INDEX IDX_12EF986DE417AC09 ON species_evolution (evolution_chain_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evolution_detail DROP FOREIGN KEY FK_16070E5DBD59BEFF');
        $this->addSql('ALTER TABLE evolution_detail DROP FOREIGN KEY FK_16070E5D95B877AF');
        $this->addSql('DROP TABLE evolution_chain_evolution_chain');
        $this->addSql('DROP TABLE item');
        $this->addSql('ALTER TABLE evolution_chain DROP FOREIGN KEY FK_68B112687D66DE33');
        $this->addSql('ALTER TABLE evolution_chain DROP FOREIGN KEY FK_68B11268ACC5F3FC');
        $this->addSql('ALTER TABLE evolution_chain DROP FOREIGN KEY FK_68B112682C2C0E8');
        $this->addSql('DROP INDEX IDX_68B112687D66DE33 ON evolution_chain');
        $this->addSql('DROP INDEX IDX_68B11268ACC5F3FC ON evolution_chain');
        $this->addSql('DROP INDEX IDX_68B112682C2C0E8 ON evolution_chain');
        $this->addSql('ALTER TABLE evolution_chain DROP evolve_to_pokemon_species_id, DROP current_pokemon_species_id, DROP evolution_detail_id');
        $this->addSql('ALTER TABLE evolution_detail DROP FOREIGN KEY FK_16070E5D708A0E0');
        $this->addSql('ALTER TABLE evolution_detail DROP FOREIGN KEY FK_16070E5D189A45FC');
        $this->addSql('ALTER TABLE evolution_detail DROP FOREIGN KEY FK_16070E5D40D850A6');
        $this->addSql('ALTER TABLE evolution_detail DROP FOREIGN KEY FK_16070E5D609DB477');
        $this->addSql('ALTER TABLE evolution_detail DROP FOREIGN KEY FK_16070E5DD7BD13B8');
        $this->addSql('ALTER TABLE evolution_detail DROP FOREIGN KEY FK_16070E5DAD65CE5B');
        $this->addSql('DROP INDEX IDX_16070E5D708A0E0 ON evolution_detail');
        $this->addSql('DROP INDEX IDX_16070E5DBD59BEFF ON evolution_detail');
        $this->addSql('DROP INDEX IDX_16070E5D95B877AF ON evolution_detail');
        $this->addSql('DROP INDEX IDX_16070E5D189A45FC ON evolution_detail');
        $this->addSql('DROP INDEX IDX_16070E5D40D850A6 ON evolution_detail');
        $this->addSql('DROP INDEX IDX_16070E5D609DB477 ON evolution_detail');
        $this->addSql('DROP INDEX IDX_16070E5DD7BD13B8 ON evolution_detail');
        $this->addSql('DROP INDEX IDX_16070E5DAD65CE5B ON evolution_detail');
        $this->addSql('ALTER TABLE evolution_detail DROP gender_id, DROP held_item_id, DROP used_item_id, DROP known_move_id, DROP known_move_type_id, DROP pokemon_species_id, DROP party_type_id, DROP evolution_trigger_id, DROP location, DROP min_affection, DROP min_beauty, DROP min_happiness, DROP min_level, DROP needs_overworld_rain, DROP time_of_day, DROP relative_physical_stats');
        $this->addSql('ALTER TABLE pokedex DROP FOREIGN KEY FK_6336F6A792AE854F');
        $this->addSql('ALTER TABLE pokedex DROP FOREIGN KEY FK_6336F6A782F1BAF4');
        $this->addSql('DROP INDEX IDX_6336F6A792AE854F ON pokedex');
        $this->addSql('DROP INDEX IDX_6336F6A782F1BAF4 ON pokedex');
        $this->addSql('ALTER TABLE pokedex DROP version_group_id, DROP language_id, DROP name, DROP slug, DROP description');
        $this->addSql('ALTER TABLE pokedex_species DROP FOREIGN KEY FK_7AD01E34609DB477');
        $this->addSql('ALTER TABLE pokedex_species DROP FOREIGN KEY FK_7AD01E34372A5D14');
        $this->addSql('DROP INDEX IDX_7AD01E34609DB477 ON pokedex_species');
        $this->addSql('DROP INDEX IDX_7AD01E34372A5D14 ON pokedex_species');
        $this->addSql('ALTER TABLE pokedex_species DROP pokemon_species_id, DROP pokedex_id');
        $this->addSql('ALTER TABLE pokemon_species DROP FOREIGN KEY FK_C9658B83B76DC94C');
        $this->addSql('ALTER TABLE pokemon_species DROP FOREIGN KEY FK_C9658B837D11D524');
        $this->addSql('DROP INDEX IDX_C9658B83B76DC94C ON pokemon_species');
        $this->addSql('DROP INDEX IDX_C9658B837D11D524 ON pokemon_species');
        $this->addSql('ALTER TABLE pokemon_species DROP egg_group_id, DROP evolves_from_species_id, DROP capture_rate, DROP growth_rate, DROP hatch_counter, DROP is_legendary, DROP has_gender_differences, DROP is_mythical, DROP is_baby, CHANGE base_happiness base_happiness INT NOT NULL');
        $this->addSql('ALTER TABLE pokemon_species_version DROP FOREIGN KEY FK_20CD1ACC609DB477');
        $this->addSql('ALTER TABLE pokemon_species_version DROP FOREIGN KEY FK_20CD1ACC4BBC2705');
        $this->addSql('DROP INDEX IDX_20CD1ACC609DB477 ON pokemon_species_version');
        $this->addSql('DROP INDEX IDX_20CD1ACC4BBC2705 ON pokemon_species_version');
        $this->addSql('ALTER TABLE pokemon_species_version DROP pokemon_species_id, DROP version_id, CHANGE description flavor_text VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE species_evolution DROP FOREIGN KEY FK_12EF986D609DB477');
        $this->addSql('ALTER TABLE species_evolution DROP FOREIGN KEY FK_12EF986DE417AC09');
        $this->addSql('DROP INDEX IDX_12EF986D609DB477 ON species_evolution');
        $this->addSql('DROP INDEX IDX_12EF986DE417AC09 ON species_evolution');
        $this->addSql('ALTER TABLE species_evolution DROP pokemon_species_id, DROP evolution_chain_id');
    }
}
