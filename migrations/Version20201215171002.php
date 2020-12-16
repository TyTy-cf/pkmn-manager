<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201215171002 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evolution_chain ADD baby_item_trigger_id INT DEFAULT NULL, DROP is_baby');
        $this->addSql('ALTER TABLE evolution_chain ADD CONSTRAINT FK_68B11268C260B660 FOREIGN KEY (baby_item_trigger_id) REFERENCES item (id)');
        $this->addSql('CREATE INDEX IDX_68B11268C260B660 ON evolution_chain (baby_item_trigger_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evolution_chain DROP FOREIGN KEY FK_68B11268C260B660');
        $this->addSql('DROP INDEX IDX_68B11268C260B660 ON evolution_chain');
        $this->addSql('ALTER TABLE evolution_chain ADD is_baby SMALLINT DEFAULT NULL, DROP baby_item_trigger_id');
    }
}
