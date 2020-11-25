<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201125075312 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abilities CHANGE name name VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE categories CHANGE name name VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE game_infos CHANGE name name VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE genre CHANGE name name VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE moves CHANGE name name VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE nature CHANGE name name VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon CHANGE name name VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE type CHANGE name name VARCHAR(36) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abilities CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE categories CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE game_infos CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE genre CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE moves CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE nature CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE pokemon CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE type CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
