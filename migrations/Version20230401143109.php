<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230401143109 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE i23_article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(100) DEFAULT NULL, quantite INTEGER DEFAULT NULL, prix INTEGER DEFAULT NULL)');
        $this->addSql('CREATE TABLE i23_panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id_id INTEGER NOT NULL, user_id_id INTEGER NOT NULL, quantite INTEGER DEFAULT NULL, CONSTRAINT FK_856ECE098F3EC46 FOREIGN KEY (article_id_id) REFERENCES i23_article (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_856ECE099D86650F FOREIGN KEY (user_id_id) REFERENCES i23_utilisateur (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_856ECE098F3EC46 ON i23_panier (article_id_id)');
        $this->addSql('CREATE INDEX IDX_856ECE099D86650F ON i23_panier (user_id_id)');
        $this->addSql('CREATE TABLE i23_utilisateur (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, nom VARCHAR(100) DEFAULT NULL, prenom VARCHAR(100) DEFAULT NULL, birthdate DATE DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_2BE047B1AA08CB10 ON i23_utilisateur (login)');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE i23_');
        $this->addSql('DROP TABLE panier');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(100) DEFAULT NULL COLLATE "BINARY", quantite INTEGER DEFAULT NULL, prix INTEGER DEFAULT NULL)');
        $this->addSql('CREATE TABLE i23_ (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(180) NOT NULL COLLATE "BINARY", roles CLOB NOT NULL COLLATE "BINARY" --(DC2Type:json)
        , password VARCHAR(255) NOT NULL COLLATE "BINARY", nom VARCHAR(100) DEFAULT NULL COLLATE "BINARY", prenom VARCHAR(100) DEFAULT NULL COLLATE "BINARY", birthdate DATE DEFAULT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_4F3A7F3EAA08CB10 ON i23_ (login)');
        $this->addSql('CREATE TABLE panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, article_id_id INTEGER NOT NULL, user_id_id INTEGER NOT NULL, quantite INTEGER DEFAULT NULL, CONSTRAINT FK_24CC0DF28F3EC46 FOREIGN KEY (article_id_id) REFERENCES article (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_24CC0DF29D86650F FOREIGN KEY (user_id_id) REFERENCES i23_ (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_24CC0DF29D86650F ON panier (user_id_id)');
        $this->addSql('CREATE INDEX IDX_24CC0DF28F3EC46 ON panier (article_id_id)');
        $this->addSql('DROP TABLE i23_article');
        $this->addSql('DROP TABLE i23_panier');
        $this->addSql('DROP TABLE i23_utilisateur');
    }
}
