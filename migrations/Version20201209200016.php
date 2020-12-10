<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201209200016 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pokemon_form (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT DEFAULT NULL, language_id INT DEFAULT NULL, is_mega TINYINT(1) DEFAULT NULL, is_default TINYINT(1) DEFAULT NULL, is_battle_only TINYINT(1) DEFAULT NULL, form_name VARCHAR(255) DEFAULT NULL, form_sprite VARCHAR(255) DEFAULT NULL, name VARCHAR(120) NOT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_6E21830C2FE71C3E (pokemon_id), INDEX IDX_6E21830C82F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pokemon_form ADD CONSTRAINT FK_6E21830C2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_form ADD CONSTRAINT FK_6E21830C82F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE pokemon_ability CHANGE hidden is_hidden TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pokemon_form');
        $this->addSql('ALTER TABLE pokemon_ability CHANGE is_hidden hidden TINYINT(1) DEFAULT NULL');
    }
}
