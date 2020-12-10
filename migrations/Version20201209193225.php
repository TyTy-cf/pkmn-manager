<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201209193225 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pokemon_ability (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT DEFAULT NULL, ability_id INT DEFAULT NULL, hidden TINYINT(1) DEFAULT NULL, INDEX IDX_59A592AD2FE71C3E (pokemon_id), INDEX IDX_59A592AD8016D8B2 (ability_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pokemon_ability ADD CONSTRAINT FK_59A592AD2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_ability ADD CONSTRAINT FK_59A592AD8016D8B2 FOREIGN KEY (ability_id) REFERENCES ability (id)');
        $this->addSql('DROP TABLE pokemon_abilities');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pokemon_abilities (pokemon_id INT NOT NULL, ability_id INT NOT NULL, INDEX IDX_89D167828016D8B2 (ability_id), INDEX IDX_89D167822FE71C3E (pokemon_id), PRIMARY KEY(pokemon_id, ability_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE pokemon_abilities ADD CONSTRAINT FK_89D167822FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_abilities ADD CONSTRAINT FK_89D167828016D8B2 FOREIGN KEY (ability_id) REFERENCES ability (id)');
        $this->addSql('DROP TABLE pokemon_ability');
    }
}
