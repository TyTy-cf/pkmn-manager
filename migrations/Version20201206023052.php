<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201206023052 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_species ADD language_id INT DEFAULT NULL, ADD genera VARCHAR(255) DEFAULT NULL, ADD name VARCHAR(36) NOT NULL, ADD slug VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE pokemon_species ADD CONSTRAINT FK_C9658B8382F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_C9658B8382F1BAF4 ON pokemon_species (language_id)');
        $this->addSql('ALTER TABLE pokemon_species_version DROP genera');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_species DROP FOREIGN KEY FK_C9658B8382F1BAF4');
        $this->addSql('DROP INDEX IDX_C9658B8382F1BAF4 ON pokemon_species');
        $this->addSql('ALTER TABLE pokemon_species DROP language_id, DROP genera, DROP name, DROP slug');
        $this->addSql('ALTER TABLE pokemon_species_version ADD genera VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
