<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240626144822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE template_question (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, type VARCHAR(50) NOT NULL, choice JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', page SMALLINT NOT NULL, creation_date DATETIME DEFAULT NULL, updated_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phonenumber VARCHAR(100) DEFAULT NULL, country VARCHAR(10) DEFAULT NULL, postalcode VARCHAR(100) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, adress LONGTEXT DEFAULT NULL, creation_date DATETIME DEFAULT NULL, updated_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649EFF286D2 (phonenumber), UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_response (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, question_id INT NOT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, type VARCHAR(50) NOT NULL, choice JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', page SMALLINT NOT NULL, response LONGTEXT DEFAULT NULL, INDEX IDX_DEF6EFFBA76ED395 (user_id), INDEX IDX_DEF6EFFB1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_response ADD CONSTRAINT FK_DEF6EFFBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_response ADD CONSTRAINT FK_DEF6EFFB1E27F6BF FOREIGN KEY (question_id) REFERENCES template_question (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_response DROP FOREIGN KEY FK_DEF6EFFBA76ED395');
        $this->addSql('ALTER TABLE user_response DROP FOREIGN KEY FK_DEF6EFFB1E27F6BF');
        $this->addSql('DROP TABLE template_question');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_response');
    }
}
