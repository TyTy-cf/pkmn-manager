<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201116164831 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abilities CHANGE name_en name_en VARCHAR(24) DEFAULT NULL, CHANGE name_fr name_fr VARCHAR(24) DEFAULT NULL');
        $this->addSql('ALTER TABLE genre CHANGE name_en name_en VARCHAR(24) DEFAULT NULL, CHANGE name_fr name_fr VARCHAR(24) DEFAULT NULL');
        $this->addSql('ALTER TABLE nature CHANGE name_en name_en VARCHAR(24) DEFAULT NULL, CHANGE name_fr name_fr VARCHAR(24) DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon DROP urlimg, CHANGE name_en name_en VARCHAR(24) DEFAULT NULL, CHANGE name_fr name_fr VARCHAR(24) DEFAULT NULL');
        $this->addSql('ALTER TABLE type CHANGE name_en name_en VARCHAR(24) DEFAULT NULL, CHANGE name_fr name_fr VARCHAR(24) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abilities CHANGE name_en name_en VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name_fr name_fr VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE genre CHANGE name_en name_en VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name_fr name_fr VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE nature CHANGE name_en name_en VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name_fr name_fr VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE pokemon ADD urlimg VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name_en name_en VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name_fr name_fr VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE type CHANGE name_en name_en VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name_fr name_fr VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
