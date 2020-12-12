<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201212011700 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon ADD version_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F392AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id)');
        $this->addSql('CREATE INDEX IDX_62DC90F392AE854F ON pokemon (version_group_id)');
        $this->addSql('ALTER TABLE pokemon_form DROP FOREIGN KEY FK_6E21830C92AE854F');
        $this->addSql('DROP INDEX IDX_6E21830C92AE854F ON pokemon_form');
        $this->addSql('ALTER TABLE pokemon_form DROP version_group_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon DROP FOREIGN KEY FK_62DC90F392AE854F');
        $this->addSql('DROP INDEX IDX_62DC90F392AE854F ON pokemon');
        $this->addSql('ALTER TABLE pokemon DROP version_group_id');
        $this->addSql('ALTER TABLE pokemon_form ADD version_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon_form ADD CONSTRAINT FK_6E21830C92AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id)');
        $this->addSql('CREATE INDEX IDX_6E21830C92AE854F ON pokemon_form (version_group_id)');
    }
}