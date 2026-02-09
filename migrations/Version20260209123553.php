<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260209123553 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Added order and racquet_ordered tables with foreign keys';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE racquet_ordered (id INT AUTO_INCREMENT NOT NULL, racquet_id INT NOT NULL, order_ref_id INT NOT NULL, quantity SMALLINT NOT NULL, INDEX IDX_A381FF5F39359B55 (racquet_id), INDEX IDX_A381FF5FE238517C (order_ref_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE racquet_ordered ADD CONSTRAINT FK_A381FF5F39359B55 FOREIGN KEY (racquet_id) REFERENCES racquet (id)');
        $this->addSql('ALTER TABLE racquet_ordered ADD CONSTRAINT FK_A381FF5FE238517C FOREIGN KEY (order_ref_id) REFERENCES `order` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE racquet_ordered DROP FOREIGN KEY FK_A381FF5FE238517C');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP TABLE racquet_ordered');
    }
}
