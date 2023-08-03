<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230803093356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE challenge (id INT NOT NULL, author_id INT NOT NULL, difficulty_id INT NOT NULL, type_id INT NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, allowed TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_D7098951F675F31B (author_id), INDEX IDX_D7098951FCFA9DAE (difficulty_id), INDEX IDX_D7098951C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE challenge_tag (challenge_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_1AC21A8F98A21AC6 (challenge_id), INDEX IDX_1AC21A8FBAD26311 (tag_id), PRIMARY KEY(challenge_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE challenge_type (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_857150AF12469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE color (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(12) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, author_id INT NOT NULL, parent_id INT NOT NULL, content LONGTEXT NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9474526CF675F31B (author_id), INDEX IDX_9474526C727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE difficulty (id INT AUTO_INCREMENT NOT NULL, color_id INT NOT NULL, label VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, sort_level SMALLINT NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_BB6B6FEF7ADA1FB5 (color_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `like` (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, target_id INT NOT NULL, INDEX IDX_AC6340B3A76ED395 (user_id), INDEX IDX_AC6340B3158E0B66 (target_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE polymorphic_entity (id INT AUTO_INCREMENT NOT NULL, type SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'1 => Challenge | 2 => Solution | 3 => Comment\' ');
        $this->addSql('CREATE TABLE refresh_tokens (id INT AUTO_INCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL, UNIQUE INDEX UNIQ_9BACE7E1C74F2195 (refresh_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resource (id INT AUTO_INCREMENT NOT NULL, target_id INT NOT NULL, label VARCHAR(255) DEFAULT NULL, value VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_BC91F416158E0B66 (target_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE solution (id INT NOT NULL, author_id INT NOT NULL, challenge_id INT NOT NULL, recap LONGTEXT DEFAULT NULL, visible TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_9F3329DBF675F31B (author_id), INDEX IDX_9F3329DB98A21AC6 (challenge_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, color_id INT NOT NULL, family_id INT NOT NULL, label VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_389B7837ADA1FB5 (color_id), INDEX IDX_389B783C35E566A (family_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag_family (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_4CA7687712469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, first_connection TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE challenge ADD CONSTRAINT FK_D7098951F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE challenge ADD CONSTRAINT FK_D7098951FCFA9DAE FOREIGN KEY (difficulty_id) REFERENCES difficulty (id)');
        $this->addSql('ALTER TABLE challenge ADD CONSTRAINT FK_D7098951C54C8C93 FOREIGN KEY (type_id) REFERENCES challenge_type (id)');
        $this->addSql('ALTER TABLE challenge ADD CONSTRAINT FK_D7098951BF396750 FOREIGN KEY (id) REFERENCES polymorphic_entity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE challenge_tag ADD CONSTRAINT FK_1AC21A8F98A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE challenge_tag ADD CONSTRAINT FK_1AC21A8FBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE challenge_type ADD CONSTRAINT FK_857150AF12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C727ACA70 FOREIGN KEY (parent_id) REFERENCES polymorphic_entity (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CBF396750 FOREIGN KEY (id) REFERENCES polymorphic_entity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE difficulty ADD CONSTRAINT FK_BB6B6FEF7ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE `like` ADD CONSTRAINT FK_AC6340B3158E0B66 FOREIGN KEY (target_id) REFERENCES polymorphic_entity (id)');
        $this->addSql('ALTER TABLE resource ADD CONSTRAINT FK_BC91F416158E0B66 FOREIGN KEY (target_id) REFERENCES polymorphic_entity (id)');
        $this->addSql('ALTER TABLE solution ADD CONSTRAINT FK_9F3329DBF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE solution ADD CONSTRAINT FK_9F3329DB98A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenge (id)');
        $this->addSql('ALTER TABLE solution ADD CONSTRAINT FK_9F3329DBBF396750 FOREIGN KEY (id) REFERENCES polymorphic_entity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B7837ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id)');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783C35E566A FOREIGN KEY (family_id) REFERENCES tag_family (id)');
        $this->addSql('ALTER TABLE tag_family ADD CONSTRAINT FK_4CA7687712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE challenge DROP FOREIGN KEY FK_D7098951F675F31B');
        $this->addSql('ALTER TABLE challenge DROP FOREIGN KEY FK_D7098951FCFA9DAE');
        $this->addSql('ALTER TABLE challenge DROP FOREIGN KEY FK_D7098951C54C8C93');
        $this->addSql('ALTER TABLE challenge DROP FOREIGN KEY FK_D7098951BF396750');
        $this->addSql('ALTER TABLE challenge_tag DROP FOREIGN KEY FK_1AC21A8F98A21AC6');
        $this->addSql('ALTER TABLE challenge_tag DROP FOREIGN KEY FK_1AC21A8FBAD26311');
        $this->addSql('ALTER TABLE challenge_type DROP FOREIGN KEY FK_857150AF12469DE2');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C727ACA70');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CBF396750');
        $this->addSql('ALTER TABLE difficulty DROP FOREIGN KEY FK_BB6B6FEF7ADA1FB5');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3A76ED395');
        $this->addSql('ALTER TABLE `like` DROP FOREIGN KEY FK_AC6340B3158E0B66');
        $this->addSql('ALTER TABLE resource DROP FOREIGN KEY FK_BC91F416158E0B66');
        $this->addSql('ALTER TABLE solution DROP FOREIGN KEY FK_9F3329DBF675F31B');
        $this->addSql('ALTER TABLE solution DROP FOREIGN KEY FK_9F3329DB98A21AC6');
        $this->addSql('ALTER TABLE solution DROP FOREIGN KEY FK_9F3329DBBF396750');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B7837ADA1FB5');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B783C35E566A');
        $this->addSql('ALTER TABLE tag_family DROP FOREIGN KEY FK_4CA7687712469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE challenge');
        $this->addSql('DROP TABLE challenge_tag');
        $this->addSql('DROP TABLE challenge_type');
        $this->addSql('DROP TABLE color');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE difficulty');
        $this->addSql('DROP TABLE `like`');
        $this->addSql('DROP TABLE polymorphic_entity');
        $this->addSql('DROP TABLE refresh_tokens');
        $this->addSql('DROP TABLE resource');
        $this->addSql('DROP TABLE solution');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE tag_family');
        $this->addSql('DROP TABLE user');
    }
}
