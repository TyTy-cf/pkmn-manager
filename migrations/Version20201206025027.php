<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201206025027 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pokemon_species_eggs (pokemon_species_id INT NOT NULL, egg_group_id_id INT NOT NULL, INDEX IDX_468B36C1609DB477 (pokemon_species_id), INDEX IDX_468B36C136FAC0C1 (egg_group_id_id), PRIMARY KEY(pokemon_species_id, egg_group_id_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pokemon_species_eggs ADD CONSTRAINT FK_468B36C1609DB477 FOREIGN KEY (pokemon_species_id) REFERENCES pokemon_species (id)');
        $this->addSql('ALTER TABLE pokemon_species_eggs ADD CONSTRAINT FK_468B36C136FAC0C1 FOREIGN KEY (egg_group_id_id) REFERENCES egg_group (id)');
        $this->addSql('ALTER TABLE pokemon_species DROP FOREIGN KEY FK_C9658B83B76DC94C');
        $this->addSql('DROP INDEX IDX_C9658B83B76DC94C ON pokemon_species');
        $this->addSql('ALTER TABLE pokemon_species DROP egg_group_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pokemon_species_eggs');
        $this->addSql('ALTER TABLE pokemon_species ADD egg_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon_species ADD CONSTRAINT FK_C9658B83B76DC94C FOREIGN KEY (egg_group_id) REFERENCES egg_group (id)');
        $this->addSql('CREATE INDEX IDX_C9658B83B76DC94C ON pokemon_species (egg_group_id)');
    }
}
