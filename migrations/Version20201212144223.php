<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201212144223 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokedex DROP FOREIGN KEY FK_6336F6A798260155');
        $this->addSql('DROP INDEX IDX_6336F6A798260155 ON pokedex');
        $this->addSql('ALTER TABLE pokedex CHANGE region_id generation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokedex ADD CONSTRAINT FK_6336F6A7553A6EC4 FOREIGN KEY (generation_id) REFERENCES generation (id)');
        $this->addSql('CREATE INDEX IDX_6336F6A7553A6EC4 ON pokedex (generation_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokedex DROP FOREIGN KEY FK_6336F6A7553A6EC4');
        $this->addSql('DROP INDEX IDX_6336F6A7553A6EC4 ON pokedex');
        $this->addSql('ALTER TABLE pokedex CHANGE generation_id region_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokedex ADD CONSTRAINT FK_6336F6A798260155 FOREIGN KEY (region_id) REFERENCES region (id)');
        $this->addSql('CREATE INDEX IDX_6336F6A798260155 ON pokedex (region_id)');
    }
}
