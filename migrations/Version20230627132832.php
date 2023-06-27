<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230627132832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, note INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE car (id INT AUTO_INCREMENT NOT NULL, marque VARCHAR(255) NOT NULL, prix INT NOT NULL, image VARCHAR(255) NOT NULL, kilometrage INT NOT NULL, annee INT NOT NULL, is_active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, first_name VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_5D9F75A1E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE hours (id INT AUTO_INCREMENT NOT NULL, days_id INT NOT NULL, matin_open VARCHAR(255) NOT NULL, matin_close VARCHAR(255) NOT NULL, aprem_open VARCHAR(255) NOT NULL, aprem_close VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8A1ABD8D3575FA99 (days_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, taux_de_reduction INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE services (id INT AUTO_INCREMENT NOT NULL, promotion_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, description LONGTEXT NOT NULL, INDEX IDX_7332E169139DF194 (promotion_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE week (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hours ADD CONSTRAINT FK_8A1ABD8D3575FA99 FOREIGN KEY (days_id) REFERENCES week (id)');
        $this->addSql('ALTER TABLE services ADD CONSTRAINT FK_7332E169139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hours DROP FOREIGN KEY FK_8A1ABD8D3575FA99');
        $this->addSql('ALTER TABLE services DROP FOREIGN KEY FK_7332E169139DF194');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE car');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE hours');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE services');
        $this->addSql('DROP TABLE week');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
