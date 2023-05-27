<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230525154753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE challenge_tag (challenge_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_1AC21A8F98A21AC6 (challenge_id), INDEX IDX_1AC21A8FBAD26311 (tag_id), PRIMARY KEY(challenge_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE challenge_tag ADD CONSTRAINT FK_1AC21A8F98A21AC6 FOREIGN KEY (challenge_id) REFERENCES challenge (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE challenge_tag ADD CONSTRAINT FK_1AC21A8FBAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE challenge_tag DROP FOREIGN KEY FK_1AC21A8F98A21AC6');
        $this->addSql('ALTER TABLE challenge_tag DROP FOREIGN KEY FK_1AC21A8FBAD26311');
        $this->addSql('DROP TABLE challenge_tag');
    }
}
