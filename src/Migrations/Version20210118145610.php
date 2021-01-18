<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210118145610 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE bookmark_tag (bookmark_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_23CB7F4A92741D25 (bookmark_id), INDEX IDX_23CB7F4ABAD26311 (tag_id), PRIMARY KEY(bookmark_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bookmark_tag ADD CONSTRAINT FK_23CB7F4A92741D25 FOREIGN KEY (bookmark_id) REFERENCES bookmark (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookmark_tag ADD CONSTRAINT FK_23CB7F4ABAD26311 FOREIGN KEY (tag_id) REFERENCES tag (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bookmark CHANGE is_favourite is_favourite TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE bookmark_tag');
        $this->addSql('ALTER TABLE bookmark CHANGE is_favourite is_favourite TINYINT(1) DEFAULT \'NULL\'');
    }
}
