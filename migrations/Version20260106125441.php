<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260106125441 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE voiture (id INT AUTO_INCREMENT NOT NULL, marque VARCHAR(100) NOT NULL, modele VARCHAR(100) NOT NULL, kilometrage INT NOT NULL, prix DOUBLE PRECISION NOT NULL, nombre_proprietaires INT NOT NULL, cylindree VARCHAR(50) NOT NULL, puissance INT NOT NULL, carburant VARCHAR(50) NOT NULL, annee_mise_en_circulation INT NOT NULL, transmission VARCHAR(50) NOT NULL, description LONGTEXT DEFAULT NULL, options LONGTEXT DEFAULT NULL, image_couverture VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voiture_image (id INT AUTO_INCREMENT NOT NULL, voiture_id INT DEFAULT NULL, image_name VARCHAR(255) NOT NULL, INDEX IDX_E890A1A8181A8BA (voiture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 (queue_name, available_at, delivered_at, id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE voiture_image ADD CONSTRAINT FK_E890A1A8181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE voiture_image DROP FOREIGN KEY FK_E890A1A8181A8BA');
        $this->addSql('DROP TABLE voiture');
        $this->addSql('DROP TABLE voiture_image');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
