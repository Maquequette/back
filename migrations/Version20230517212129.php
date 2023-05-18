<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230517212129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE challenge (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, difficulty_id INT NOT NULL, type_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, allowed TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D7098951F675F31B (author_id), INDEX IDX_D7098951FCFA9DAE (difficulty_id), INDEX IDX_D7098951C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE challenge_like (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, challenge_id INT NOT NULL, INDEX IDX_A5CC4735A76ED395 (user_id), INDEX IDX_A5CC473598A21AC6 (challenge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE challenge_type (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_857150AF12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE color (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(12) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, parent_id INT DEFAULT NULL, content LONGTEXT NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526C727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `comment_like` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, comments_id INT DEFAULT NULL, INDEX IDX_8A55E25FA76ED395 (user_id), INDEX IDX_8A55E25F63379586 (comments_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE difficulty (id INT AUTO_INCREMENT NOT NULL, color_id INT NOT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, sort_level SMALLINT NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_BB6B6FEF7ADA1FB5 (color_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ressource (id INT AUTO_INCREMENT NOT NULL, challenge_id INT DEFAULT NULL, solution_id INT DEFAULT NULL, label VARCHAR(255) DEFAULT NULL, value VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_939F454498A21AC6 (challenge_id), INDEX IDX_939F45441C0BE183 (solution_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE solution (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, challenge_id INT NOT NULL, visible TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9F3329DBF675F31B (author_id), INDEX IDX_9F3329DB98A21AC6 (challenge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE solution_like (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, solution_id INT NOT NULL, INDEX IDX_2AD5348A76ED395 (user_id), INDEX IDX_2AD53481C0BE183 (solution_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, color_id INT NOT NULL, family_id INT NOT NULL, label VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_389B7837ADA1FB5 (color_id), INDEX IDX_389B783C35E566A (family_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_family (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE challenge ADD CONSTRAINT FK_D7098951F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE challenge ADD CONSTRAINT FK_D7098951FCFA9DAE FOREIGN KEY (difficulty_id) REFERENCES difficulty (id)');
        $this->addSql('ALTER TABLE challenge ADD CONSTRAINT FK_D7098951C54C8C93 FOREIGN KEY (type_id) REFERENCES challenge_type (id)');
        $this->addSql('ALTER TABLE challenge_like ADD CONSTRAINT FK_A5CC4735A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE challenge_like ADD CONSTRAINT FK_A5CC473598A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenge (id)');
        $this->addSql('ALTER TABLE challenge_type ADD CONSTRAINT FK_857150AF12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C727ACA70 FOREIGN KEY (parent_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE `comment_like` ADD CONSTRAINT FK_8A55E25FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `comment_like` ADD CONSTRAINT FK_8A55E25F63379586 FOREIGN KEY (comments_id) REFERENCES comment (id)');
        $this->addSql('ALTER TABLE difficulty ADD CONSTRAINT FK_BB6B6FEF7ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id)');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F454498A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenge (id)');
        $this->addSql('ALTER TABLE ressource ADD CONSTRAINT FK_939F45441C0BE183 FOREIGN KEY (solution_id) REFERENCES solution (id)');
        $this->addSql('ALTER TABLE solution ADD CONSTRAINT FK_9F3329DBF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE solution ADD CONSTRAINT FK_9F3329DB98A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenge (id)');
        $this->addSql('ALTER TABLE solution_like ADD CONSTRAINT FK_2AD5348A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE solution_like ADD CONSTRAINT FK_2AD53481C0BE183 FOREIGN KEY (solution_id) REFERENCES solution (id)');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B7837ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id)');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783C35E566A FOREIGN KEY (family_id) REFERENCES tag_family (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE challenge DROP FOREIGN KEY FK_D7098951F675F31B');
        $this->addSql('ALTER TABLE challenge DROP FOREIGN KEY FK_D7098951FCFA9DAE');
        $this->addSql('ALTER TABLE challenge DROP FOREIGN KEY FK_D7098951C54C8C93');
        $this->addSql('ALTER TABLE challenge_like DROP FOREIGN KEY FK_A5CC4735A76ED395');
        $this->addSql('ALTER TABLE challenge_like DROP FOREIGN KEY FK_A5CC473598A21AC6');
        $this->addSql('ALTER TABLE challenge_type DROP FOREIGN KEY FK_857150AF12469DE2');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C727ACA70');
        $this->addSql('ALTER TABLE `comment_like` DROP FOREIGN KEY FK_8A55E25FA76ED395');
        $this->addSql('ALTER TABLE `comment_like` DROP FOREIGN KEY FK_8A55E25F63379586');
        $this->addSql('ALTER TABLE difficulty DROP FOREIGN KEY FK_BB6B6FEF7ADA1FB5');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F454498A21AC6');
        $this->addSql('ALTER TABLE ressource DROP FOREIGN KEY FK_939F45441C0BE183');
        $this->addSql('ALTER TABLE solution DROP FOREIGN KEY FK_9F3329DBF675F31B');
        $this->addSql('ALTER TABLE solution DROP FOREIGN KEY FK_9F3329DB98A21AC6');
        $this->addSql('ALTER TABLE solution_like DROP FOREIGN KEY FK_2AD5348A76ED395');
        $this->addSql('ALTER TABLE solution_like DROP FOREIGN KEY FK_2AD53481C0BE183');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B7837ADA1FB5');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B783C35E566A');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE challenge');
        $this->addSql('DROP TABLE challenge_like');
        $this->addSql('DROP TABLE challenge_type');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE `comment_like`');
        $this->addSql('DROP TABLE difficulty');
        $this->addSql('DROP TABLE ressource');
        $this->addSql('DROP TABLE solution');
        $this->addSql('DROP TABLE solution_like');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_family');
        $this->addSql('DROP TABLE user');
    }
}
