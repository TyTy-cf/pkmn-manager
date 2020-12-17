<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201217093724 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE evolution_chain_link_chain');
        $this->addSql('ALTER TABLE evolution_chain DROP FOREIGN KEY FK_68B11268527729B9');
        $this->addSql('DROP INDEX IDX_68B11268527729B9 ON evolution_chain');
        $this->addSql('ALTER TABLE evolution_chain DROP evolution_chain_link_id');
        $this->addSql('ALTER TABLE evolution_chain_link ADD evolution_chain_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evolution_chain_link ADD CONSTRAINT FK_51633FFAE417AC09 FOREIGN KEY (evolution_chain_id) REFERENCES evolution_chain (id)');
        $this->addSql('CREATE INDEX IDX_51633FFAE417AC09 ON evolution_chain_link (evolution_chain_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evolution_chain_link_chain (evolution_chain_link_from_id INT NOT NULL, evolution_chain_link_to_id INT NOT NULL, INDEX IDX_E8AFFD146998AFBB (evolution_chain_link_to_id), INDEX IDX_E8AFFD14B7157EC0 (evolution_chain_link_from_id), PRIMARY KEY(evolution_chain_link_from_id, evolution_chain_link_to_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE evolution_chain_link_chain ADD CONSTRAINT FK_E8AFFD146998AFBB FOREIGN KEY (evolution_chain_link_to_id) REFERENCES evolution_chain_link (id)');
        $this->addSql('ALTER TABLE evolution_chain_link_chain ADD CONSTRAINT FK_E8AFFD14B7157EC0 FOREIGN KEY (evolution_chain_link_from_id) REFERENCES evolution_chain_link (id)');
        $this->addSql('ALTER TABLE evolution_chain ADD evolution_chain_link_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE evolution_chain ADD CONSTRAINT FK_68B11268527729B9 FOREIGN KEY (evolution_chain_link_id) REFERENCES evolution_chain_link (id)');
        $this->addSql('CREATE INDEX IDX_68B11268527729B9 ON evolution_chain (evolution_chain_link_id)');
        $this->addSql('ALTER TABLE evolution_chain_link DROP FOREIGN KEY FK_51633FFAE417AC09');
        $this->addSql('DROP INDEX IDX_51633FFAE417AC09 ON evolution_chain_link');
        $this->addSql('ALTER TABLE evolution_chain_link DROP evolution_chain_id');
    }
}
