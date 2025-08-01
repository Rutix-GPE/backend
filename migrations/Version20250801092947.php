<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250801092947 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, content VARCHAR(255) NOT NULL, is_root_question TINYINT(1) NOT NULL, is_quick_question TINYINT(1) NOT NULL, creation_date DATETIME DEFAULT NULL, updated_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_B6F7494E5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relation (id INT AUTO_INCREMENT NOT NULL, source_id INT DEFAULT NULL, target_question_id INT DEFAULT NULL, target_routine_id INT DEFAULT NULL, answer VARCHAR(50) NOT NULL, creation_date DATETIME DEFAULT NULL, updated_date DATETIME DEFAULT NULL, INDEX IDX_62894749953C1C61 (source_id), INDEX IDX_62894749991DB9B5 (target_question_id), INDEX IDX_62894749C0964C56 (target_routine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE routine (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, days JSON NOT NULL, task_time TIME NOT NULL, creation_date DATETIME DEFAULT NULL, updated_date DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, next_root_question_id INT DEFAULT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phonenumber VARCHAR(100) DEFAULT NULL, country VARCHAR(10) DEFAULT NULL, postalcode VARCHAR(100) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, adress LONGTEXT DEFAULT NULL, avatar_file VARCHAR(255) DEFAULT NULL, creation_date DATETIME DEFAULT NULL, updated_date DATETIME DEFAULT NULL, memo LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649EFF286D2 (phonenumber), INDEX IDX_8D93D6491F6D0E56 (next_root_question_id), UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_routine (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, days JSON NOT NULL, task_time TIME NOT NULL, is_all_task_generated TINYINT(1) NOT NULL, creation_date DATETIME NOT NULL, updated_date DATETIME NOT NULL, INDEX IDX_13FBC6DCA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_task (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, task_date_time DATETIME NOT NULL, status BIGINT NOT NULL, creation_date DATETIME NOT NULL, updated_date DATETIME NOT NULL, INDEX IDX_28FF97ECA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749953C1C61 FOREIGN KEY (source_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749991DB9B5 FOREIGN KEY (target_question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE relation ADD CONSTRAINT FK_62894749C0964C56 FOREIGN KEY (target_routine_id) REFERENCES routine (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6491F6D0E56 FOREIGN KEY (next_root_question_id) REFERENCES question (id)');
        $this->addSql('ALTER TABLE user_routine ADD CONSTRAINT FK_13FBC6DCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_task ADD CONSTRAINT FK_28FF97ECA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE relation DROP FOREIGN KEY FK_62894749953C1C61');
        $this->addSql('ALTER TABLE relation DROP FOREIGN KEY FK_62894749991DB9B5');
        $this->addSql('ALTER TABLE relation DROP FOREIGN KEY FK_62894749C0964C56');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6491F6D0E56');
        $this->addSql('ALTER TABLE user_routine DROP FOREIGN KEY FK_13FBC6DCA76ED395');
        $this->addSql('ALTER TABLE user_task DROP FOREIGN KEY FK_28FF97ECA76ED395');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE relation');
        $this->addSql('DROP TABLE routine');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_routine');
        $this->addSql('DROP TABLE user_task');
    }
}
