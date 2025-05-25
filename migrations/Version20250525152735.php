<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250525152735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, hex_color VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE condition_routine (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, question_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, task_time TIME NOT NULL, days JSON NOT NULL COMMENT \'(DC2Type:json)\', response_condition VARCHAR(255) NOT NULL, creation_date DATETIME DEFAULT NULL, updated_date DATETIME DEFAULT NULL, INDEX IDX_F656BA712469DE2 (category_id), INDEX IDX_F656BA71E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question_v2 (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, content VARCHAR(255) NOT NULL, is_root_question TINYINT(1) NOT NULL, is_quick_question TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_51750CE05E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE relation_v2 (id INT AUTO_INCREMENT NOT NULL, source_id INT DEFAULT NULL, target_question_id INT DEFAULT NULL, target_routine_id INT DEFAULT NULL, answer VARCHAR(50) NOT NULL, INDEX IDX_7D046B04953C1C61 (source_id), INDEX IDX_7D046B04991DB9B5 (target_question_id), INDEX IDX_7D046B04C0964C56 (target_routine_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE routine (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, task_time TIME NOT NULL, creation_date DATETIME NOT NULL, updated_date DATETIME NOT NULL, days JSON NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_4BF6D8D612469DE2 (category_id), INDEX IDX_4BF6D8D6A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE routine_v2 (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, days JSON NOT NULL COMMENT \'(DC2Type:json)\', task_time TIME NOT NULL, UNIQUE INDEX UNIQ_BD286E985E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, task_date DATE NOT NULL, task_time TIME NOT NULL, status VARCHAR(50) NOT NULL, creation_date DATE NOT NULL, updated_date DATE NOT NULL, INDEX IDX_527EDB2512469DE2 (category_id), INDEX IDX_527EDB25A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE template_question (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, type VARCHAR(50) NOT NULL, choice JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', page SMALLINT NOT NULL, creation_date DATETIME DEFAULT NULL, updated_date DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_E9A1793D5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phonenumber VARCHAR(100) DEFAULT NULL, country VARCHAR(10) DEFAULT NULL, postalcode VARCHAR(100) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, adress LONGTEXT DEFAULT NULL, avatar_file VARCHAR(255) DEFAULT NULL, creation_date DATETIME DEFAULT NULL, updated_date DATETIME DEFAULT NULL, memo LONGTEXT DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649EFF286D2 (phonenumber), UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_response (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, question_id INT NOT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, type VARCHAR(50) NOT NULL, choice JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', page SMALLINT NOT NULL, response LONGTEXT DEFAULT NULL, creation_date DATETIME DEFAULT NULL, updated_date DATETIME DEFAULT NULL, INDEX IDX_DEF6EFFBA76ED395 (user_id), INDEX IDX_DEF6EFFB1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_routine_v2 (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, days LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', task_time TIME NOT NULL, is_all_task_generated TINYINT(1) NOT NULL, INDEX IDX_1A4FA874A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_task_v2 (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, task_date_time DATETIME NOT NULL, status BIGINT NOT NULL, INDEX IDX_9AE9E5D3A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE condition_routine ADD CONSTRAINT FK_F656BA712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE condition_routine ADD CONSTRAINT FK_F656BA71E27F6BF FOREIGN KEY (question_id) REFERENCES template_question (id)');
        $this->addSql('ALTER TABLE relation_v2 ADD CONSTRAINT FK_7D046B04953C1C61 FOREIGN KEY (source_id) REFERENCES question_v2 (id)');
        $this->addSql('ALTER TABLE relation_v2 ADD CONSTRAINT FK_7D046B04991DB9B5 FOREIGN KEY (target_question_id) REFERENCES question_v2 (id)');
        $this->addSql('ALTER TABLE relation_v2 ADD CONSTRAINT FK_7D046B04C0964C56 FOREIGN KEY (target_routine_id) REFERENCES routine_v2 (id)');
        $this->addSql('ALTER TABLE routine ADD CONSTRAINT FK_4BF6D8D612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE routine ADD CONSTRAINT FK_4BF6D8D6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2512469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_response ADD CONSTRAINT FK_DEF6EFFBA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_response ADD CONSTRAINT FK_DEF6EFFB1E27F6BF FOREIGN KEY (question_id) REFERENCES template_question (id)');
        $this->addSql('ALTER TABLE user_routine_v2 ADD CONSTRAINT FK_1A4FA874A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_task_v2 ADD CONSTRAINT FK_9AE9E5D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE condition_routine DROP FOREIGN KEY FK_F656BA712469DE2');
        $this->addSql('ALTER TABLE condition_routine DROP FOREIGN KEY FK_F656BA71E27F6BF');
        $this->addSql('ALTER TABLE relation_v2 DROP FOREIGN KEY FK_7D046B04953C1C61');
        $this->addSql('ALTER TABLE relation_v2 DROP FOREIGN KEY FK_7D046B04991DB9B5');
        $this->addSql('ALTER TABLE relation_v2 DROP FOREIGN KEY FK_7D046B04C0964C56');
        $this->addSql('ALTER TABLE routine DROP FOREIGN KEY FK_4BF6D8D612469DE2');
        $this->addSql('ALTER TABLE routine DROP FOREIGN KEY FK_4BF6D8D6A76ED395');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2512469DE2');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25A76ED395');
        $this->addSql('ALTER TABLE user_response DROP FOREIGN KEY FK_DEF6EFFBA76ED395');
        $this->addSql('ALTER TABLE user_response DROP FOREIGN KEY FK_DEF6EFFB1E27F6BF');
        $this->addSql('ALTER TABLE user_routine_v2 DROP FOREIGN KEY FK_1A4FA874A76ED395');
        $this->addSql('ALTER TABLE user_task_v2 DROP FOREIGN KEY FK_9AE9E5D3A76ED395');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE condition_routine');
        $this->addSql('DROP TABLE question_v2');
        $this->addSql('DROP TABLE relation_v2');
        $this->addSql('DROP TABLE routine');
        $this->addSql('DROP TABLE routine_v2');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE template_question');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_response');
        $this->addSql('DROP TABLE user_routine_v2');
        $this->addSql('DROP TABLE user_task_v2');
    }
}
