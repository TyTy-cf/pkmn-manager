<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201129114542 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_moves_learn_version DROP FOREIGN KEY FK_7364724FB5C0D298');
        $this->addSql('DROP INDEX IDX_7364724FB5C0D298 ON pokemon_moves_learn_version');
        $this->addSql('ALTER TABLE pokemon_moves_learn_version CHANGE gameinfos_id version_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon_moves_learn_version ADD CONSTRAINT FK_7364724F92AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id)');
        $this->addSql('CREATE INDEX IDX_7364724F92AE854F ON pokemon_moves_learn_version (version_group_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_moves_learn_version DROP FOREIGN KEY FK_7364724F92AE854F');
        $this->addSql('DROP INDEX IDX_7364724F92AE854F ON pokemon_moves_learn_version');
        $this->addSql('ALTER TABLE pokemon_moves_learn_version CHANGE version_group_id gameinfos_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon_moves_learn_version ADD CONSTRAINT FK_7364724FB5C0D298 FOREIGN KEY (gameinfos_id) REFERENCES version_group (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_7364724FB5C0D298 ON pokemon_moves_learn_version (gameinfos_id)');
    }
}
