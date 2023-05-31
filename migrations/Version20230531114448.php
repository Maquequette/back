<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230531114448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag_family ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE tag_family ADD CONSTRAINT FK_4CA7687712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_4CA7687712469DE2 ON tag_family (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tag_family DROP FOREIGN KEY FK_4CA7687712469DE2');
        $this->addSql('DROP INDEX IDX_4CA7687712469DE2 ON tag_family');
        $this->addSql('ALTER TABLE tag_family DROP category_id');
    }
}
