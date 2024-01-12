<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240112110900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE task_list (id INT AUTO_INCREMENT NOT NULL, uuid CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task ADD task_list_id INT NOT NULL');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25224F3C61 FOREIGN KEY (task_list_id) REFERENCES task_list (id)');
        $this->addSql('CREATE INDEX IDX_527EDB25224F3C61 ON task (task_list_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25224F3C61');
        $this->addSql('DROP TABLE task_list');
        $this->addSql('DROP INDEX IDX_527EDB25224F3C61 ON task');
        $this->addSql('ALTER TABLE task DROP task_list_id');
    }
}
