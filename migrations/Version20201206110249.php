<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201206110249 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon CHANGE name name VARCHAR(36) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('CREATE INDEX slug_idx ON pokemon (slug)');
        $this->addSql('CREATE INDEX slug_idx ON pokemon_species_version (slug)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX slug_idx ON pokemon');
        $this->addSql('ALTER TABLE pokemon CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX slug_idx ON pokemon_species_version');
    }
}
