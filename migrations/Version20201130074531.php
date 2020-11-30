<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130074531 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pokemon_sprites_version (id INT AUTO_INCREMENT NOT NULL, pokemon_id INT DEFAULT NULL, version_id INT DEFAULT NULL, url_default VARCHAR(255) DEFAULT NULL, url_default_shiny VARCHAR(255) DEFAULT NULL, url_default_female VARCHAR(255) DEFAULT NULL, url_default_female_shiny VARCHAR(255) DEFAULT NULL, INDEX IDX_66ECDBE32FE71C3E (pokemon_id), INDEX IDX_66ECDBE34BBC2705 (version_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pokemon_sprites_version ADD CONSTRAINT FK_66ECDBE32FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('ALTER TABLE pokemon_sprites_version ADD CONSTRAINT FK_66ECDBE34BBC2705 FOREIGN KEY (version_id) REFERENCES version (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE pokemon_sprites_version');
    }
}
