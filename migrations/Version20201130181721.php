<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201130181721 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_sprites_version DROP FOREIGN KEY FK_66ECDBE34BBC2705');
        $this->addSql('DROP INDEX IDX_66ECDBE34BBC2705 ON pokemon_sprites_version');
        $this->addSql('ALTER TABLE pokemon_sprites_version CHANGE version_id version_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon_sprites_version ADD CONSTRAINT FK_66ECDBE392AE854F FOREIGN KEY (version_group_id) REFERENCES version_group (id)');
        $this->addSql('CREATE INDEX IDX_66ECDBE392AE854F ON pokemon_sprites_version (version_group_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_sprites_version DROP FOREIGN KEY FK_66ECDBE392AE854F');
        $this->addSql('DROP INDEX IDX_66ECDBE392AE854F ON pokemon_sprites_version');
        $this->addSql('ALTER TABLE pokemon_sprites_version CHANGE version_group_id version_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon_sprites_version ADD CONSTRAINT FK_66ECDBE34BBC2705 FOREIGN KEY (version_id) REFERENCES version (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_66ECDBE34BBC2705 ON pokemon_sprites_version (version_id)');
    }
}
