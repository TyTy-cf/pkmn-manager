<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201215185111 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evolution_chain ADD evolution_chain_link_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evolution_chain ADD CONSTRAINT FK_68B11268527729B9 FOREIGN KEY (evolution_chain_link_id) REFERENCES evolution_chain_link (id)');
        $this->addSql('CREATE INDEX IDX_68B11268527729B9 ON evolution_chain (evolution_chain_link_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evolution_chain DROP FOREIGN KEY FK_68B11268527729B9');
        $this->addSql('DROP INDEX IDX_68B11268527729B9 ON evolution_chain');
        $this->addSql('ALTER TABLE evolution_chain DROP evolution_chain_link_id');
    }
}
