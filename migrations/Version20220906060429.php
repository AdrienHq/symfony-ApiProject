<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220906060429 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F85E0677 ON user (username)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__books AS SELECT id, title, description, number_of_pages, date_of_release, update_date_of_release, author, is_published, price FROM books');
        $this->addSql('DROP TABLE books');
        $this->addSql('CREATE TABLE books (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, number_of_pages INTEGER NOT NULL, date_of_release DATETIME NOT NULL, update_date_of_release DATETIME DEFAULT NULL, author VARCHAR(255) NOT NULL, is_published BOOLEAN NOT NULL, price INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO books (id, title, description, number_of_pages, date_of_release, update_date_of_release, author, is_published, price) SELECT id, title, description, number_of_pages, date_of_release, update_date_of_release, author, is_published, price FROM __temp__books');
        $this->addSql('DROP TABLE __temp__books');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TEMPORARY TABLE __temp__books AS SELECT id, title, description, number_of_pages, date_of_release, update_date_of_release, author, is_published, price FROM books');
        $this->addSql('DROP TABLE books');
        $this->addSql('CREATE TABLE books (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, number_of_pages INTEGER NOT NULL, date_of_release DATETIME NOT NULL, update_date_of_release DATETIME DEFAULT NULL, author VARCHAR(255) NOT NULL, is_published BOOLEAN DEFAULT FALSE, price INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO books (id, title, description, number_of_pages, date_of_release, update_date_of_release, author, is_published, price) SELECT id, title, description, number_of_pages, date_of_release, update_date_of_release, author, is_published, price FROM __temp__books');
        $this->addSql('DROP TABLE __temp__books');
    }
}
