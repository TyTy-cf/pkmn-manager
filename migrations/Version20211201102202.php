<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211201102202 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_sheet ADD version INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon_sheet ADD CONSTRAINT FK_68D528B6BF1CD3C3 FOREIGN KEY (version) REFERENCES version (id)');
        $this->addSql('CREATE INDEX IDX_68D528B6BF1CD3C3 ON pokemon_sheet (version)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_sheet DROP FOREIGN KEY FK_68D528B6BF1CD3C3');
        $this->addSql('DROP INDEX IDX_68D528B6BF1CD3C3 ON pokemon_sheet');
        $this->addSql('ALTER TABLE pokemon_sheet DROP version');
    }
}