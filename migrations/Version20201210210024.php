<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201210210024 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, language_id INT DEFAULT NULL, name VARCHAR(120) DEFAULT NULL, slug VARCHAR(255) NOT NULL, INDEX IDX_F62F17682F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE region ADD CONSTRAINT FK_F62F17682F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('ALTER TABLE pokedex ADD region_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokedex ADD CONSTRAINT FK_6336F6A798260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('CREATE INDEX IDX_6336F6A798260155 ON pokedex (region_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokedex DROP FOREIGN KEY FK_6336F6A798260155');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP INDEX IDX_6336F6A798260155 ON pokedex');
        $this->addSql('ALTER TABLE pokedex DROP region_id');
    }
}
