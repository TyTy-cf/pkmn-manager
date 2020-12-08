<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201208001125 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_held_pokemon (id INT AUTO_INCREMENT NOT NULL, item_id INT DEFAULT NULL, pokemon_id INT DEFAULT NULL, INDEX IDX_2B08193126F525E (item_id), INDEX IDX_2B081932FE71C3E (pokemon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_held_pokemon_version (id INT AUTO_INCREMENT NOT NULL, version_id INT DEFAULT NULL, rarity INT DEFAULT NULL, item_held_by_Pokemon_id INT DEFAULT NULL, INDEX IDX_243020FF4BBC2705 (version_id), INDEX IDX_243020FFDC1DFE7B (item_held_by_Pokemon_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_held_pokemon ADD CONSTRAINT FK_2B08193126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE item_held_pokemon ADD CONSTRAINT FK_2B081932FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE item_held_pokemon_version ADD CONSTRAINT FK_243020FF4BBC2705 FOREIGN KEY (version_id) REFERENCES version (id)');
        $this->addSql('ALTER TABLE item_held_pokemon_version ADD CONSTRAINT FK_243020FFDC1DFE7B FOREIGN KEY (item_held_by_Pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('DROP TABLE item_pokemon');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_pokemon (item_id INT NOT NULL, pokemon_id INT NOT NULL, INDEX IDX_6981B6A72FE71C3E (pokemon_id), INDEX IDX_6981B6A7126F525E (item_id), PRIMARY KEY(item_id, pokemon_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE item_pokemon ADD CONSTRAINT FK_6981B6A7126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_pokemon ADD CONSTRAINT FK_6981B6A72FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE item_held_pokemon');
        $this->addSql('DROP TABLE item_held_pokemon_version');
    }
}
