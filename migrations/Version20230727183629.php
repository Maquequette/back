<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230727183629 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solution ADD recap LONGTEXT DEFAULT NULL, CHANGE id id INT NOT NULL');
        $this->addSql('ALTER TABLE solution ADD CONSTRAINT FK_9F3329DBBF396750 FOREIGN KEY (id) REFERENCES polymorphic_entity (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE solution DROP FOREIGN KEY FK_9F3329DBBF396750');
        $this->addSql('ALTER TABLE solution DROP recap, CHANGE id id INT AUTO_INCREMENT NOT NULL');
    }
}
