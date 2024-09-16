<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240913133524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE routine (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, routine_day_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, routine_time TIME NOT NULL, status VARCHAR(50) NOT NULL, creation_date DATETIME NOT NULL, updated_date DATETIME NOT NULL, INDEX IDX_4BF6D8D612469DE2 (category_id), INDEX IDX_4BF6D8D64DEE7459 (routine_day_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE routine_day (id INT AUTO_INCREMENT NOT NULL, day_of_week VARCHAR(20) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, name VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, task_date DATE NOT NULL, task_time TIME NOT NULL, status VARCHAR(50) NOT NULL, creation_date DATE NOT NULL, updated_date DATE NOT NULL, INDEX IDX_527EDB2512469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE routine ADD CONSTRAINT FK_4BF6D8D612469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE routine ADD CONSTRAINT FK_4BF6D8D64DEE7459 FOREIGN KEY (routine_day_id) REFERENCES routine_day (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB2512469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE routine DROP FOREIGN KEY FK_4BF6D8D612469DE2');
        $this->addSql('ALTER TABLE routine DROP FOREIGN KEY FK_4BF6D8D64DEE7459');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB2512469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE routine');
        $this->addSql('DROP TABLE routine_day');
        $this->addSql('DROP TABLE task');
    }
}
