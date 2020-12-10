<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201210082845 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pokemon_sprites (id INT AUTO_INCREMENT NOT NULL, sprite_icon VARCHAR(255) DEFAULT NULL, sprite_official VARCHAR(255) DEFAULT NULL, sprite_front_default VARCHAR(255) DEFAULT NULL, sprite_front_shiny VARCHAR(255) DEFAULT NULL, sprite_front_female VARCHAR(255) DEFAULT NULL, sprite_female_shiny VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pokemon ADD pokemon_sprites_id INT DEFAULT NULL, DROP url_icon, DROP url_sprite_img');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3DDBC24E FOREIGN KEY (pokemon_sprites_id) REFERENCES pokemon_sprites (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_62DC90F3DDBC24E ON pokemon (pokemon_sprites_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F3DDBC24E');
        $this->addSql('DROP TABLE pokemon_sprites');
        $this->addSql('DROP INDEX UNIQ_62DC90F3DDBC24E ON pokemon');
        $this->addSql('ALTER TABLE pokemon ADD url_icon VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD url_sprite_img VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP pokemon_sprites_id');
    }
}
