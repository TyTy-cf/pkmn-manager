<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201206110455 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE INDEX slug_idx ON ability (slug)');
        $this->addSql('CREATE INDEX slug_idx ON pokedex (slug)');
        $this->addSql('CREATE INDEX slug_idx ON type (slug)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX slug_idx ON ability');
        $this->addSql('DROP INDEX slug_idx ON pokedex');
        $this->addSql('DROP INDEX slug_idx ON type');
    }
}
