<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210629195203 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nature CHANGE atk atk DOUBLE PRECISION DEFAULT NULL, CHANGE def def DOUBLE PRECISION DEFAULT NULL, CHANGE spa spa DOUBLE PRECISION DEFAULT NULL, CHANGE spd spd DOUBLE PRECISION DEFAULT NULL, CHANGE spe spe DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE nature CHANGE atk atk INT DEFAULT NULL, CHANGE def def INT DEFAULT NULL, CHANGE spa spa INT DEFAULT NULL, CHANGE spd spd INT DEFAULT NULL, CHANGE spe spe INT DEFAULT NULL');
    }
}
