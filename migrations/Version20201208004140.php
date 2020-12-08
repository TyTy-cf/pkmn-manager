<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201208004140 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_held_pokemon_version DROP FOREIGN KEY FK_243020FFDC1DFE7B');
        $this->addSql('DROP INDEX IDX_243020FFDC1DFE7B ON item_held_pokemon_version');
        $this->addSql('ALTER TABLE item_held_pokemon_version CHANGE item_held_by_pokemon_id item_held_Pokemon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item_held_pokemon_version ADD CONSTRAINT FK_243020FF3FB0DACD FOREIGN KEY (item_held_Pokemon_id) REFERENCES item_held_pokemon (id)');
        $this->addSql('CREATE INDEX IDX_243020FF3FB0DACD ON item_held_pokemon_version (item_held_Pokemon_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE item_held_pokemon_version DROP FOREIGN KEY FK_243020FF3FB0DACD');
        $this->addSql('DROP INDEX IDX_243020FF3FB0DACD ON item_held_pokemon_version');
        $this->addSql('ALTER TABLE item_held_pokemon_version CHANGE item_held_pokemon_id item_held_by_Pokemon_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE item_held_pokemon_version ADD CONSTRAINT FK_243020FFDC1DFE7B FOREIGN KEY (item_held_by_Pokemon_id) REFERENCES item_held_pokemon_version (id)');
        $this->addSql('CREATE INDEX IDX_243020FFDC1DFE7B ON item_held_pokemon_version (item_held_by_Pokemon_id)');
    }
}
