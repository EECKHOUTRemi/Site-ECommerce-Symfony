<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260224074352 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add promo code entity and relation to order';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE promo_code (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, discount SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD promo_code_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F52993982FAE4625 FOREIGN KEY (promo_code_id) REFERENCES promo_code (id)');
        $this->addSql('CREATE INDEX IDX_F52993982FAE4625 ON `order` (promo_code_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F52993982FAE4625');
        $this->addSql('DROP TABLE promo_code');
        $this->addSql('DROP INDEX IDX_F52993982FAE4625 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP promo_code_id');
    }
}
