<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201210183604 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokedex_version_group DROP FOREIGN KEY FK_66CF0887372A5D14');
        $this->addSql('ALTER TABLE pokedex_version_group DROP FOREIGN KEY FK_66CF088792AE854F');
        $this->addSql('ALTER TABLE pokedex_version_group ADD CONSTRAINT FK_66CF0887372A5D14 FOREIGN KEY (pokedex_id) REFERENCES pokedex (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokedex_version_group ADD CONSTRAINT FK_66CF088792AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokedex_version_group DROP FOREIGN KEY FK_66CF0887372A5D14');
        $this->addSql('ALTER TABLE pokedex_version_group DROP FOREIGN KEY FK_66CF088792AE854F');
        $this->addSql('ALTER TABLE pokedex_version_group ADD CONSTRAINT FK_66CF0887372A5D14 FOREIGN KEY (pokedex_id) REFERENCES pokedex (id)');
        $this->addSql('ALTER TABLE pokedex_version_group ADD CONSTRAINT FK_66CF088792AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id)');
    }
}
