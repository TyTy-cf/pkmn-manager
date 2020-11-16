<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201116164618 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abilities ADD name_en VARCHAR(24) NOT NULL, ADD name_fr VARCHAR(24) NOT NULL, DROP name');
        $this->addSql('ALTER TABLE genre ADD name_en VARCHAR(24) NOT NULL, ADD name_fr VARCHAR(24) NOT NULL, DROP name');
        $this->addSql('ALTER TABLE nature ADD name_fr VARCHAR(24) NOT NULL, DROP name');
        $this->addSql('ALTER TABLE pokemon ADD url_img VARCHAR(255) DEFAULT NULL, ADD name_en VARCHAR(24) NOT NULL, ADD name_fr VARCHAR(24) NOT NULL, DROP name');
        $this->addSql('ALTER TABLE type ADD name_en VARCHAR(24) NOT NULL, ADD name_fr VARCHAR(24) NOT NULL, DROP name');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abilities ADD name VARCHAR(8) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP name_en, DROP name_fr');
        $this->addSql('ALTER TABLE genre ADD name VARCHAR(8) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP name_en, DROP name_fr');
        $this->addSql('ALTER TABLE nature ADD name VARCHAR(8) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP name_fr');
        $this->addSql('ALTER TABLE pokemon ADD name VARCHAR(120) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP url_img, DROP name_en, DROP name_fr');
        $this->addSql('ALTER TABLE type ADD name VARCHAR(8) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, DROP name_en, DROP name_fr');
    }
}
