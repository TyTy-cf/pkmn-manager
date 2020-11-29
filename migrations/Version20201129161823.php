<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201129161823 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE version (id INT AUTO_INCREMENT NOT NULL, version_group_id INT DEFAULT NULL, language_id INT DEFAULT NULL, name VARCHAR(36) DEFAULT NULL, slug VARCHAR(36) DEFAULT NULL, INDEX IDX_BF1CD3C392AE854F (version_group_id), INDEX IDX_BF1CD3C382F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE version ADD CONSTRAINT FK_BF1CD3C392AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id)');
        $this->addSql('ALTER TABLE version ADD CONSTRAINT FK_BF1CD3C382F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE version');
    }
}
