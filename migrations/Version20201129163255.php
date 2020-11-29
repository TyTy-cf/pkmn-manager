<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201129163255 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ability CHANGE slug slug VARCHAR(56) DEFAULT NULL');
        $this->addSql('ALTER TABLE damage_class CHANGE slug slug VARCHAR(56) DEFAULT NULL');
        $this->addSql('ALTER TABLE generation CHANGE slug slug VARCHAR(56) DEFAULT NULL');
        $this->addSql('ALTER TABLE move CHANGE slug slug VARCHAR(56) DEFAULT NULL');
        $this->addSql('ALTER TABLE move_learn_method CHANGE slug slug VARCHAR(56) DEFAULT NULL');
        $this->addSql('ALTER TABLE move_machine CHANGE slug slug VARCHAR(56) DEFAULT NULL');
        $this->addSql('ALTER TABLE nature CHANGE slug slug VARCHAR(56) DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon CHANGE slug slug VARCHAR(56) DEFAULT NULL');
        $this->addSql('ALTER TABLE type CHANGE slug slug VARCHAR(56) DEFAULT NULL');
        $this->addSql('ALTER TABLE type_damage_relation_type CHANGE slug slug VARCHAR(56) DEFAULT NULL');
        $this->addSql('ALTER TABLE version CHANGE slug slug VARCHAR(56) DEFAULT NULL');
        $this->addSql('ALTER TABLE version_group CHANGE slug slug VARCHAR(56) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ability CHANGE slug slug VARCHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE damage_class CHANGE slug slug VARCHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE generation CHANGE slug slug VARCHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE move CHANGE slug slug VARCHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE move_learn_method CHANGE slug slug VARCHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE move_machine CHANGE slug slug VARCHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE nature CHANGE slug slug VARCHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE pokemon CHANGE slug slug VARCHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE type CHANGE slug slug VARCHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE type_damage_relation_type CHANGE slug slug VARCHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE version CHANGE slug slug VARCHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE version_group CHANGE slug slug VARCHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
