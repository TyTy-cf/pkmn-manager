<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201126181228 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type_damage_from_type CHANGE type_id type_id INT DEFAULT NULL, CHANGE damage_from_type_id damage_from_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type_damage_from_type ADD CONSTRAINT FK_6FC4951AC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id)');
        $this->addSql('ALTER TABLE type_damage_from_type ADD CONSTRAINT FK_6FC4951A617B28A0 FOREIGN KEY (damage_from_type_id) REFERENCES type (id)');
        $this->addSql('CREATE INDEX IDX_6FC4951AC54C8C93 ON type_damage_from_type (type_id)');
        $this->addSql('CREATE INDEX IDX_6FC4951A617B28A0 ON type_damage_from_type (damage_from_type_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE type_damage_from_type DROP FOREIGN KEY FK_6FC4951AC54C8C93');
        $this->addSql('ALTER TABLE type_damage_from_type DROP FOREIGN KEY FK_6FC4951A617B28A0');
        $this->addSql('DROP INDEX IDX_6FC4951AC54C8C93 ON type_damage_from_type');
        $this->addSql('DROP INDEX IDX_6FC4951A617B28A0 ON type_damage_from_type');
        $this->addSql('ALTER TABLE type_damage_from_type CHANGE type_id type_id INT NOT NULL, CHANGE damage_from_type_id damage_from_type_id INT NOT NULL');
    }
}
