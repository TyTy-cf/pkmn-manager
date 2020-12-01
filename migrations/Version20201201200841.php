<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201201200841 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE move_description (id INT AUTO_INCREMENT NOT NULL, move_id INT DEFAULT NULL, version_group_id INT DEFAULT NULL, description VARCHAR(255) DEFAULT NULL, INDEX IDX_83FBA7FC6DC541A8 (move_id), INDEX IDX_83FBA7FC92AE854F (version_group_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE move_description ADD CONSTRAINT FK_83FBA7FC6DC541A8 FOREIGN KEY (move_id) REFERENCES move (id)');
        $this->addSql('ALTER TABLE move_description ADD CONSTRAINT FK_83FBA7FC92AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE move_description');
    }
}
