<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210629193818 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evolution_trigger CHANGE title title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE move_learn_method CHANGE code_method code_method VARCHAR(80) DEFAULT NULL');
        $this->addSql('ALTER TABLE nature ADD atk INT DEFAULT NULL, ADD def INT DEFAULT NULL, ADD spa INT DEFAULT NULL, ADD spd INT DEFAULT NULL, ADD spe INT DEFAULT NULL, DROP stat_increased, DROP stat_decreased');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evolution_trigger CHANGE title title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE move_learn_method CHANGE code_method code_method VARCHAR(80) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE nature ADD stat_increased VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, ADD stat_decreased VARCHAR(120) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP atk, DROP def, DROP spa, DROP spd, DROP spe');
    }
}
