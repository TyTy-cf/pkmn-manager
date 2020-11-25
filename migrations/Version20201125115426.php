<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201125115426 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abilities ADD language_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE abilities ADD CONSTRAINT FK_B8388DA482F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_B8388DA482F1BAF4 ON abilities (language_id)');
        $this->addSql('ALTER TABLE categories ADD language_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF3466882F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_3AF3466882F1BAF4 ON categories (language_id)');
        $this->addSql('ALTER TABLE game_infos ADD language_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE game_infos ADD CONSTRAINT FK_DCF9FBE282F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_DCF9FBE282F1BAF4 ON game_infos (language_id)');
        $this->addSql('ALTER TABLE genre ADD language_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE genre ADD CONSTRAINT FK_835033F882F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_835033F882F1BAF4 ON genre (language_id)');
        $this->addSql('ALTER TABLE moves ADD language_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE moves ADD CONSTRAINT FK_453F083282F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_453F083282F1BAF4 ON moves (language_id)');
        $this->addSql('ALTER TABLE type ADD language_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE type ADD CONSTRAINT FK_8CDE572982F1BAF4 FOREIGN KEY (language_id) REFERENCES language (id)');
        $this->addSql('CREATE INDEX IDX_8CDE572982F1BAF4 ON type (language_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE abilities DROP FOREIGN KEY FK_B8388DA482F1BAF4');
        $this->addSql('DROP INDEX IDX_B8388DA482F1BAF4 ON abilities');
        $this->addSql('ALTER TABLE abilities DROP language_id');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF3466882F1BAF4');
        $this->addSql('DROP INDEX IDX_3AF3466882F1BAF4 ON categories');
        $this->addSql('ALTER TABLE categories DROP language_id');
        $this->addSql('ALTER TABLE game_infos DROP FOREIGN KEY FK_DCF9FBE282F1BAF4');
        $this->addSql('DROP INDEX IDX_DCF9FBE282F1BAF4 ON game_infos');
        $this->addSql('ALTER TABLE game_infos DROP language_id');
        $this->addSql('ALTER TABLE genre DROP FOREIGN KEY FK_835033F882F1BAF4');
        $this->addSql('DROP INDEX IDX_835033F882F1BAF4 ON genre');
        $this->addSql('ALTER TABLE genre DROP language_id');
        $this->addSql('ALTER TABLE moves DROP FOREIGN KEY FK_453F083282F1BAF4');
        $this->addSql('DROP INDEX IDX_453F083282F1BAF4 ON moves');
        $this->addSql('ALTER TABLE moves DROP language_id');
        $this->addSql('ALTER TABLE type DROP FOREIGN KEY FK_8CDE572982F1BAF4');
        $this->addSql('DROP INDEX IDX_8CDE572982F1BAF4 ON type');
        $this->addSql('ALTER TABLE type DROP language_id');
    }
}
