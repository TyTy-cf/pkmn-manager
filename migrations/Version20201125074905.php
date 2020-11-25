<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201125074905 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abilities ADD name VARCHAR(36) NOT NULL, CHANGE name_en name_en VARCHAR(36) DEFAULT NULL, CHANGE name_fr name_fr VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE categories ADD name VARCHAR(36) NOT NULL, CHANGE name_en name_en VARCHAR(36) DEFAULT NULL, CHANGE name_fr name_fr VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE game_infos ADD name VARCHAR(36) NOT NULL, CHANGE name_en name_en VARCHAR(36) DEFAULT NULL, CHANGE name_fr name_fr VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE genre ADD name VARCHAR(36) NOT NULL, CHANGE name_en name_en VARCHAR(36) DEFAULT NULL, CHANGE name_fr name_fr VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE moves ADD name VARCHAR(36) NOT NULL, CHANGE name_en name_en VARCHAR(36) DEFAULT NULL, CHANGE name_fr name_fr VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE nature ADD name VARCHAR(36) NOT NULL, CHANGE name_en name_en VARCHAR(36) DEFAULT NULL, CHANGE name_fr name_fr VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon ADD language_id INT DEFAULT NULL, ADD name VARCHAR(36) NOT NULL, CHANGE name_en name_en VARCHAR(36) DEFAULT NULL, CHANGE name_fr name_fr VARCHAR(36) DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F382F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_62DC90F382F1BAF4 ON pokemon (language_id)');
        $this->addSql('ALTER TABLE type ADD name VARCHAR(36) NOT NULL, CHANGE name_en name_en VARCHAR(36) DEFAULT NULL, CHANGE name_fr name_fr VARCHAR(36) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abilities DROP name, CHANGE name_en name_en VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name_fr name_fr VARCHAR(24) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE categories DROP name, CHANGE name_en name_en VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name_fr name_fr VARCHAR(24) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE game_infos DROP name, CHANGE name_en name_en VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name_fr name_fr VARCHAR(24) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE genre DROP name, CHANGE name_en name_en VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name_fr name_fr VARCHAR(24) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE moves DROP name, CHANGE name_en name_en VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name_fr name_fr VARCHAR(24) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE nature DROP name, CHANGE name_en name_en VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name_fr name_fr VARCHAR(24) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F382F1BAF4');
        $this->addSql('DROP INDEX IDX_62DC90F382F1BAF4 ON pokemon');
        $this->addSql('ALTER TABLE pokemon DROP language_id, DROP name, CHANGE name_en name_en VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name_fr name_fr VARCHAR(24) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE type DROP name, CHANGE name_en name_en VARCHAR(24) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE name_fr name_fr VARCHAR(24) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
