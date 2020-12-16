<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201215184911 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evolution_chain_link (id INT AUTO_INCREMENT NOT NULL, from_pokemon_species_id INT DEFAULT NULL, evolution_detail_id INT DEFAULT NULL, is_baby SMALLINT NOT NULL, `order` INT NOT NULL, INDEX IDX_51633FFAF1095A32 (from_pokemon_species_id), INDEX IDX_51633FFA2C2C0E8 (evolution_detail_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evolution_chain_link_chain (evolution_chain_link_from_id INT NOT NULL, evolution_chain_link_to_id INT NOT NULL, INDEX IDX_E8AFFD14B7157EC0 (evolution_chain_link_from_id), INDEX IDX_E8AFFD146998AFBB (evolution_chain_link_to_id), PRIMARY KEY(evolution_chain_link_from_id, evolution_chain_link_to_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evolution_chain_link ADD CONSTRAINT FK_51633FFAF1095A32 FOREIGN KEY (from_pokemon_species_id) REFERENCES pokedex_species (id)');
        $this->addSql('ALTER TABLE evolution_chain_link ADD CONSTRAINT FK_51633FFA2C2C0E8 FOREIGN KEY (evolution_detail_id) REFERENCES evolution_detail (id)');
        $this->addSql('ALTER TABLE evolution_chain_link_chain ADD CONSTRAINT FK_E8AFFD14B7157EC0 FOREIGN KEY (evolution_chain_link_from_id) REFERENCES evolution_chain_link (id)');
        $this->addSql('ALTER TABLE evolution_chain_link_chain ADD CONSTRAINT FK_E8AFFD146998AFBB FOREIGN KEY (evolution_chain_link_to_id) REFERENCES evolution_chain_link (id)');
        $this->addSql('DROP TABLE evolution_chain_chain');
        $this->addSql('ALTER TABLE evolution_chain DROP FOREIGN KEY FK_68B112682C2C0E8');
        $this->addSql('ALTER TABLE evolution_chain DROP FOREIGN KEY FK_68B112687D66DE33');
        $this->addSql('ALTER TABLE evolution_chain DROP FOREIGN KEY FK_68B11268F1095A32');
        $this->addSql('DROP INDEX IDX_68B11268F1095A32 ON evolution_chain');
        $this->addSql('DROP INDEX IDX_68B112687D66DE33 ON evolution_chain');
        $this->addSql('DROP INDEX IDX_68B112682C2C0E8 ON evolution_chain');
        $this->addSql('ALTER TABLE evolution_chain DROP evolve_to_pokemon_species_id, DROP from_pokemon_species_id, DROP evolution_detail_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evolution_chain_link_chain DROP FOREIGN KEY FK_E8AFFD14B7157EC0');
        $this->addSql('ALTER TABLE evolution_chain_link_chain DROP FOREIGN KEY FK_E8AFFD146998AFBB');
        $this->addSql('CREATE TABLE evolution_chain_chain (id INT AUTO_INCREMENT NOT NULL, evolution_detail_from_id INT DEFAULT NULL, evolution_detail_to_id INT DEFAULT NULL, `order` INT NOT NULL, INDEX IDX_26B70EE4603FF7A6 (evolution_detail_from_id), INDEX IDX_26B70EE43051E4CA (evolution_detail_to_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE evolution_chain_chain ADD CONSTRAINT FK_26B70EE43051E4CA FOREIGN KEY (evolution_detail_to_id) REFERENCES evolution_detail (id)');
        $this->addSql('ALTER TABLE evolution_chain_chain ADD CONSTRAINT FK_26B70EE4603FF7A6 FOREIGN KEY (evolution_detail_from_id) REFERENCES evolution_detail (id)');
        $this->addSql('DROP TABLE evolution_chain_link');
        $this->addSql('DROP TABLE evolution_chain_link_chain');
        $this->addSql('ALTER TABLE evolution_chain ADD evolve_to_pokemon_species_id INT DEFAULT NULL, ADD from_pokemon_species_id INT DEFAULT NULL, ADD evolution_detail_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evolution_chain ADD CONSTRAINT FK_68B112682C2C0E8 FOREIGN KEY (evolution_detail_id) REFERENCES evolution_detail (id)');
        $this->addSql('ALTER TABLE evolution_chain ADD CONSTRAINT FK_68B112687D66DE33 FOREIGN KEY (evolve_to_pokemon_species_id) REFERENCES pokedex_species (id)');
        $this->addSql('ALTER TABLE evolution_chain ADD CONSTRAINT FK_68B11268F1095A32 FOREIGN KEY (from_pokemon_species_id) REFERENCES pokedex_species (id)');
        $this->addSql('CREATE INDEX IDX_68B11268F1095A32 ON evolution_chain (from_pokemon_species_id)');
        $this->addSql('CREATE INDEX IDX_68B112687D66DE33 ON evolution_chain (evolve_to_pokemon_species_id)');
        $this->addSql('CREATE INDEX IDX_68B112682C2C0E8 ON evolution_chain (evolution_detail_id)');
    }
}
