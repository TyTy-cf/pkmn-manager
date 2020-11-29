<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201129115928 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE machine_move (id INT AUTO_INCREMENT NOT NULL, move_id INT DEFAULT NULL, version_group_id INT DEFAULT NULL, language_id INT DEFAULT NULL, name VARCHAR(36) DEFAULT NULL, slug VARCHAR(36) DEFAULT NULL, INDEX IDX_B934DC016DC541A8 (move_id), INDEX IDX_B934DC0192AE854F (version_group_id), INDEX IDX_B934DC0182F1BAF4 (language_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE machine_move ADD CONSTRAINT FK_B934DC016DC541A8 FOREIGN KEY (move_id) REFERENCES move (id)');
        $this->addSql('ALTER TABLE machine_move ADD CONSTRAINT FK_B934DC0192AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id)');
        $this->addSql('ALTER TABLE machine_move ADD CONSTRAINT FK_B934DC0182F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE machine_move');
    }
}
