<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201211182244 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE generation ADD main_region_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE generation ADD CONSTRAINT FK_D3266C3BB890A6F7 FOREIGN KEY (main_region_id) REFERENCES region (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D3266C3BB890A6F7 ON generation (main_region_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE generation DROP FOREIGN KEY FK_D3266C3BB890A6F7');
        $this->addSql('DROP INDEX UNIQ_D3266C3BB890A6F7 ON generation');
        $this->addSql('ALTER TABLE generation DROP main_region_id');
    }
}
