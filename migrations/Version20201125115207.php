<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201125115207 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genre ADD image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE nature ADD language_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE nature ADD CONSTRAINT FK_B1D882A782F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_B1D882A782F1BAF4 ON nature (language_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE genre DROP image');
        $this->addSql('ALTER TABLE nature DROP FOREIGN KEY FK_B1D882A782F1BAF4');
        $this->addSql('DROP INDEX IDX_B1D882A782F1BAF4 ON nature');
        $this->addSql('ALTER TABLE nature DROP language_id');
    }
}
