<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260220085225 extends AbstractMigration
{
    public function getDescription(): string
    {
        return ' Move head_size, string_pattern, weight and grip_size from Racquet to RacquetOrdered and update foreign keys in RacquetRating';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DELETE FROM `racquet_ordered` WHERE 1=1');
        $this->addSql('DELETE FROM `order` WHERE 1=1');
        $this->addSql('ALTER TABLE racquet DROP head_size, DROP string_pattern, DROP weight, DROP grip_size');
        $this->addSql('ALTER TABLE racquet_ordered ADD head_size INT DEFAULT NULL, ADD string_pattern VARCHAR(5) DEFAULT NULL, ADD weight INT DEFAULT NULL, ADD grip_size SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE racquet_rating DROP FOREIGN KEY FK_AF083E0F9D86650F');
        $this->addSql('ALTER TABLE racquet_rating DROP FOREIGN KEY FK_AF083E0FC830BEA0');
        $this->addSql('DROP INDEX idx_af083e0f9d86650f ON racquet_rating');
        $this->addSql('CREATE INDEX IDX_FBE735BDA76ED395 ON racquet_rating (user_id)');
        $this->addSql('DROP INDEX idx_af083e0fc830bea0 ON racquet_rating');
        $this->addSql('CREATE INDEX IDX_FBE735BD39359B55 ON racquet_rating (racquet_id)');
        $this->addSql('ALTER TABLE racquet_rating ADD CONSTRAINT FK_AF083E0F9D86650F FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE racquet_rating ADD CONSTRAINT FK_AF083E0FC830BEA0 FOREIGN KEY (racquet_id) REFERENCES racquet (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE racquet ADD head_size INT DEFAULT NULL, ADD string_pattern VARCHAR(5) DEFAULT NULL, ADD weight INT DEFAULT NULL, ADD grip_size SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE racquet_ordered DROP head_size, DROP string_pattern, DROP weight, DROP grip_size');
        $this->addSql('ALTER TABLE racquet_rating DROP FOREIGN KEY FK_FBE735BDA76ED395');
        $this->addSql('ALTER TABLE racquet_rating DROP FOREIGN KEY FK_FBE735BD39359B55');
        $this->addSql('DROP INDEX idx_fbe735bda76ed395 ON racquet_rating');
        $this->addSql('CREATE INDEX IDX_AF083E0F9D86650F ON racquet_rating (user_id)');
        $this->addSql('DROP INDEX idx_fbe735bd39359b55 ON racquet_rating');
        $this->addSql('CREATE INDEX IDX_AF083E0FC830BEA0 ON racquet_rating (racquet_id)');
        $this->addSql('ALTER TABLE racquet_rating ADD CONSTRAINT FK_FBE735BDA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE racquet_rating ADD CONSTRAINT FK_FBE735BD39359B55 FOREIGN KEY (racquet_id) REFERENCES racquet (id)');
    }
}
