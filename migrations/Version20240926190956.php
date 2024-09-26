<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240926190956 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE routine DROP FOREIGN KEY FK_4BF6D8D64DEE7459');
        $this->addSql('CREATE TABLE condition_routine (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, task_time TIME NOT NULL, days JSON NOT NULL COMMENT \'(DC2Type:json)\', response_condition VARCHAR(255) NOT NULL, creation_date DATETIME DEFAULT NULL, updated_date DATETIME DEFAULT NULL, INDEX IDX_F656BA712469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE condition_routine_template_question (condition_routine_id INT NOT NULL, template_question_id INT NOT NULL, INDEX IDX_E6F84B101A7A961 (condition_routine_id), INDEX IDX_E6F84B1015DEE2DB (template_question_id), PRIMARY KEY(condition_routine_id, template_question_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE condition_routine ADD CONSTRAINT FK_F656BA712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE condition_routine_template_question ADD CONSTRAINT FK_E6F84B101A7A961 FOREIGN KEY (condition_routine_id) REFERENCES condition_routine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE condition_routine_template_question ADD CONSTRAINT FK_E6F84B1015DEE2DB FOREIGN KEY (template_question_id) REFERENCES template_question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_routine DROP FOREIGN KEY FK_13FBC6DC52E8E1D5');
        $this->addSql('ALTER TABLE user_routine DROP FOREIGN KEY FK_13FBC6DCF27A94C7');
        $this->addSql('DROP TABLE routine_day');
        $this->addSql('DROP TABLE user_routine');
        $this->addSql('ALTER TABLE category ADD hex_color VARCHAR(255) DEFAULT NULL');
        $this->addSql('DROP INDEX IDX_4BF6D8D64DEE7459 ON routine');
        $this->addSql('ALTER TABLE routine ADD days JSON NOT NULL COMMENT \'(DC2Type:json)\', DROP routine_day_id, DROP status, CHANGE routine_time task_time TIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE routine_day (id INT AUTO_INCREMENT NOT NULL, day_of_week SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_routine (id INT AUTO_INCREMENT NOT NULL, user_response_id INT NOT NULL, routine_id INT NOT NULL, response VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_13FBC6DCF27A94C7 (routine_id), UNIQUE INDEX UNIQ_13FBC6DC52E8E1D5 (user_response_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_routine ADD CONSTRAINT FK_13FBC6DC52E8E1D5 FOREIGN KEY (user_response_id) REFERENCES user_response (id)');
        $this->addSql('ALTER TABLE user_routine ADD CONSTRAINT FK_13FBC6DCF27A94C7 FOREIGN KEY (routine_id) REFERENCES routine (id)');
        $this->addSql('ALTER TABLE condition_routine DROP FOREIGN KEY FK_F656BA712469DE2');
        $this->addSql('ALTER TABLE condition_routine_template_question DROP FOREIGN KEY FK_E6F84B101A7A961');
        $this->addSql('ALTER TABLE condition_routine_template_question DROP FOREIGN KEY FK_E6F84B1015DEE2DB');
        $this->addSql('DROP TABLE condition_routine');
        $this->addSql('DROP TABLE condition_routine_template_question');
        $this->addSql('ALTER TABLE category DROP hex_color');
        $this->addSql('ALTER TABLE routine ADD routine_day_id INT DEFAULT NULL, ADD status VARCHAR(50) NOT NULL, DROP days, CHANGE task_time routine_time TIME NOT NULL');
        $this->addSql('ALTER TABLE routine ADD CONSTRAINT FK_4BF6D8D64DEE7459 FOREIGN KEY (routine_day_id) REFERENCES routine_day (id)');
        $this->addSql('CREATE INDEX IDX_4BF6D8D64DEE7459 ON routine (routine_day_id)');
    }
}
