<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923101741 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_routine_routine DROP FOREIGN KEY FK_99D4F4C1551D522B');
        $this->addSql('ALTER TABLE user_routine_routine DROP FOREIGN KEY FK_99D4F4C1F27A94C7');
        $this->addSql('ALTER TABLE user_routine_user_response DROP FOREIGN KEY FK_40229936551D522B');
        $this->addSql('ALTER TABLE user_routine_user_response DROP FOREIGN KEY FK_4022993652E8E1D5');
        $this->addSql('DROP TABLE user_routine_routine');
        $this->addSql('DROP TABLE user_routine_user_response');
        $this->addSql('ALTER TABLE routine_day CHANGE day_of_week day_of_week SMALLINT NOT NULL');
        $this->addSql('ALTER TABLE user_routine ADD user_response_id INT NOT NULL, ADD routine_id INT NOT NULL');
        $this->addSql('ALTER TABLE user_routine ADD CONSTRAINT FK_13FBC6DC52E8E1D5 FOREIGN KEY (user_response_id) REFERENCES user_response (id)');
        $this->addSql('ALTER TABLE user_routine ADD CONSTRAINT FK_13FBC6DCF27A94C7 FOREIGN KEY (routine_id) REFERENCES routine (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_13FBC6DC52E8E1D5 ON user_routine (user_response_id)');
        $this->addSql('CREATE INDEX IDX_13FBC6DCF27A94C7 ON user_routine (routine_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_routine_routine (user_routine_id INT NOT NULL, routine_id INT NOT NULL, INDEX IDX_99D4F4C1F27A94C7 (routine_id), INDEX IDX_99D4F4C1551D522B (user_routine_id), PRIMARY KEY(user_routine_id, routine_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_routine_user_response (user_routine_id INT NOT NULL, user_response_id INT NOT NULL, INDEX IDX_40229936551D522B (user_routine_id), INDEX IDX_4022993652E8E1D5 (user_response_id), PRIMARY KEY(user_routine_id, user_response_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_routine_routine ADD CONSTRAINT FK_99D4F4C1551D522B FOREIGN KEY (user_routine_id) REFERENCES user_routine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_routine_routine ADD CONSTRAINT FK_99D4F4C1F27A94C7 FOREIGN KEY (routine_id) REFERENCES routine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_routine_user_response ADD CONSTRAINT FK_40229936551D522B FOREIGN KEY (user_routine_id) REFERENCES user_routine (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_routine_user_response ADD CONSTRAINT FK_4022993652E8E1D5 FOREIGN KEY (user_response_id) REFERENCES user_response (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE routine_day CHANGE day_of_week day_of_week VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE user_routine DROP FOREIGN KEY FK_13FBC6DC52E8E1D5');
        $this->addSql('ALTER TABLE user_routine DROP FOREIGN KEY FK_13FBC6DCF27A94C7');
        $this->addSql('DROP INDEX UNIQ_13FBC6DC52E8E1D5 ON user_routine');
        $this->addSql('DROP INDEX IDX_13FBC6DCF27A94C7 ON user_routine');
        $this->addSql('ALTER TABLE user_routine DROP user_response_id, DROP routine_id');
    }
}
