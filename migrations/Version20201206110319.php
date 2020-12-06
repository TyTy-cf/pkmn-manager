<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201206110319 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ability CHANGE name name VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE damage_class CHANGE name name VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE egg_group CHANGE name name VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE evolution_trigger CHANGE name name VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE generation CHANGE name name VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE item CHANGE name name VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE nature CHANGE name name VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE pokedex CHANGE name name VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE pokemon CHANGE name name VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE pokemon_species CHANGE name name VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE type CHANGE name name VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE version CHANGE name name VARCHAR(120) NOT NULL');
        $this->addSql('ALTER TABLE version_group CHANGE name name VARCHAR(120) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ability CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE damage_class CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE egg_group CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE evolution_trigger CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE generation CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE item CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE nature CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE pokedex CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE pokemon CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE pokemon_species CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE type CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE version CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE version_group CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
