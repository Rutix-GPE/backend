<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240926200301 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE condition_routine_template_question DROP FOREIGN KEY FK_E6F84B1015DEE2DB');
        $this->addSql('ALTER TABLE condition_routine_template_question DROP FOREIGN KEY FK_E6F84B101A7A961');
        $this->addSql('DROP TABLE condition_routine_template_question');
        $this->addSql('ALTER TABLE condition_routine ADD question_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE condition_routine ADD CONSTRAINT FK_F656BA71E27F6BF FOREIGN KEY (question_id) REFERENCES template_question (id)');
        $this->addSql('CREATE INDEX IDX_F656BA71E27F6BF ON condition_routine (question_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE condition_routine_template_question (condition_routine_id INT NOT NULL, template_question_id INT NOT NULL, INDEX IDX_E6F84B1015DEE2DB (template_question_id), INDEX IDX_E6F84B101A7A961 (condition_routine_id), PRIMARY KEY(condition_routine_id, template_question_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE condition_routine_template_question ADD CONSTRAINT FK_E6F84B1015DEE2DB FOREIGN KEY (template_question_id) REFERENCES template_question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE condition_routine_template_question ADD CONSTRAINT FK_E6F84B101A7A961 FOREIGN KEY (condition_routine_id) REFERENCES condition_routine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE condition_routine DROP FOREIGN KEY FK_F656BA71E27F6BF');
        $this->addSql('DROP INDEX IDX_F656BA71E27F6BF ON condition_routine');
        $this->addSql('ALTER TABLE condition_routine DROP question_id');
    }
}
