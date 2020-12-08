<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201207195329 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_pokemon (item_id INT NOT NULL, pokemon_id INT NOT NULL, INDEX IDX_6981B6A7126F525E (item_id), INDEX IDX_6981B6A72FE71C3E (pokemon_id), PRIMARY KEY(item_id, pokemon_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_pokemon ADD CONSTRAINT FK_6981B6A7126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_pokemon ADD CONSTRAINT FK_6981B6A72FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EC7139AED');
        $this->addSql('DROP INDEX IDX_1F1B251EC7139AED ON item');
        $this->addSql('ALTER TABLE item DROP held_by_pokemon_id, DROP fling_power, DROP fling_effect');
        $this->addSql('ALTER TABLE move CHANGE name name VARCHAR(120) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE move_learn_method CHANGE name name VARCHAR(120) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE move_machine CHANGE name name VARCHAR(120) NOT NULL, CHANGE slug slug VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE item_pokemon');
        $this->addSql('ALTER TABLE item ADD held_by_pokemon_id INT DEFAULT NULL, ADD fling_power INT DEFAULT NULL, ADD fling_effect VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EC7139AED FOREIGN KEY (held_by_pokemon_id) REFERENCES pokemon (id)');
        $this->addSql('CREATE INDEX IDX_1F1B251EC7139AED ON item (held_by_pokemon_id)');
        $this->addSql('ALTER TABLE move CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE move_learn_method CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE move_machine CHANGE name name VARCHAR(36) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE slug slug VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
