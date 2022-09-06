<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220906062909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__books AS SELECT id, title, description, number_of_pages, date_of_release, update_date_of_release, author, is_published, price FROM books');
        $this->addSql('DROP TABLE books');
        $this->addSql('CREATE TABLE books (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, owner_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, number_of_pages INTEGER NOT NULL, date_of_release DATETIME NOT NULL, update_date_of_release DATETIME DEFAULT NULL, author VARCHAR(255) NOT NULL, is_published BOOLEAN NOT NULL, price INTEGER DEFAULT NULL, CONSTRAINT FK_4A1B2A927E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO books (id, title, description, number_of_pages, date_of_release, update_date_of_release, author, is_published, price) SELECT id, title, description, number_of_pages, date_of_release, update_date_of_release, author, is_published, price FROM __temp__books');
        $this->addSql('DROP TABLE __temp__books');
        $this->addSql('CREATE INDEX IDX_4A1B2A927E3C61F9 ON books (owner_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__books AS SELECT id, title, description, number_of_pages, date_of_release, update_date_of_release, author, is_published, price FROM books');
        $this->addSql('DROP TABLE books');
        $this->addSql('CREATE TABLE books (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, number_of_pages INTEGER NOT NULL, date_of_release DATETIME NOT NULL, update_date_of_release DATETIME DEFAULT NULL, author VARCHAR(255) NOT NULL, is_published BOOLEAN NOT NULL, price INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO books (id, title, description, number_of_pages, date_of_release, update_date_of_release, author, is_published, price) SELECT id, title, description, number_of_pages, date_of_release, update_date_of_release, author, is_published, price FROM __temp__books');
        $this->addSql('DROP TABLE __temp__books');
    }
}
