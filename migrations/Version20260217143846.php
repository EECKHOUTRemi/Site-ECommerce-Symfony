<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260217143846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add RacquetRating entity and relation with User and Racquet, add avg_rating to Racquet, add relation between Order and User';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE racquet_rating (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, racquet_id INT NOT NULL, rating SMALLINT NOT NULL, INDEX IDX_AF083E0F9D86650F (user_id), INDEX IDX_AF083E0FC830BEA0 (racquet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE racquet_rating ADD CONSTRAINT FK_AF083E0F9D86650F FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE racquet_rating ADD CONSTRAINT FK_AF083E0FC830BEA0 FOREIGN KEY (racquet_id) REFERENCES racquet (id)');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F5299398A76ED395 ON `order` (user_id)');
        $this->addSql('ALTER TABLE racquet ADD avg_rating SMALLINT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE racquet_rating');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398A76ED395');
        $this->addSql('DROP INDEX IDX_F5299398A76ED395 ON `order`');
        $this->addSql('ALTER TABLE racquet DROP avg_rating');
    }
}
