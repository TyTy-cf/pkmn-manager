<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201206025110 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_species_eggs DROP FOREIGN KEY FK_468B36C136FAC0C1');
        $this->addSql('DROP INDEX IDX_468B36C136FAC0C1 ON pokemon_species_eggs');
        $this->addSql('ALTER TABLE pokemon_species_eggs DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE pokemon_species_eggs CHANGE egg_group_id_id egg_group_id INT NOT NULL');
        $this->addSql('ALTER TABLE pokemon_species_eggs ADD CONSTRAINT FK_468B36C1B76DC94C FOREIGN KEY (egg_group_id) REFERENCES egg_group (id)');
        $this->addSql('CREATE INDEX IDX_468B36C1B76DC94C ON pokemon_species_eggs (egg_group_id)');
        $this->addSql('ALTER TABLE pokemon_species_eggs ADD PRIMARY KEY (pokemon_species_id, egg_group_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_species_eggs DROP FOREIGN KEY FK_468B36C1B76DC94C');
        $this->addSql('DROP INDEX IDX_468B36C1B76DC94C ON pokemon_species_eggs');
        $this->addSql('ALTER TABLE pokemon_species_eggs DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE pokemon_species_eggs CHANGE egg_group_id egg_group_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE pokemon_species_eggs ADD CONSTRAINT FK_468B36C136FAC0C1 FOREIGN KEY (egg_group_id_id) REFERENCES egg_group (id)');
        $this->addSql('CREATE INDEX IDX_468B36C136FAC0C1 ON pokemon_species_eggs (egg_group_id_id)');
        $this->addSql('ALTER TABLE pokemon_species_eggs ADD PRIMARY KEY (pokemon_species_id, egg_group_id_id)');
    }
}
