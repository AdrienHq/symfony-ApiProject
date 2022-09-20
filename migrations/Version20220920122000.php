<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220920122000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE books_notification (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, books_id INTEGER NOT NULL, notification_text VARCHAR(255) DEFAULT NULL, CONSTRAINT FK_6777C59A7DD8AC20 FOREIGN KEY (books_id) REFERENCES books (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_6777C59A7DD8AC20 ON books_notification (books_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE books_notification');
    }
}
