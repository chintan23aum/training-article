<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240309093934 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article_log (id INT AUTO_INCREMENT NOT NULL, user_id_id INT NOT NULL, article_id_id INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_1D204BBF9D86650F (user_id_id), INDEX IDX_1D204BBF8F3EC46 (article_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article_log ADD CONSTRAINT FK_1D204BBF9D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article_log ADD CONSTRAINT FK_1D204BBF8F3EC46 FOREIGN KEY (article_id_id) REFERENCES article (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article_log DROP FOREIGN KEY FK_1D204BBF9D86650F');
        $this->addSql('ALTER TABLE article_log DROP FOREIGN KEY FK_1D204BBF8F3EC46');
        $this->addSql('DROP TABLE article_log');
    }
}
