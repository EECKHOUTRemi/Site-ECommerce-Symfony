<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260220090406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Change head_size, string_pattern, weight and grip_size columns in racquet_ordered table to not nullable. Also change head_size, weight and grip_size to smallint with length.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE racquet_ordered CHANGE head_size head_size SMALLINT NOT NULL, CHANGE string_pattern string_pattern VARCHAR(5) NOT NULL, CHANGE weight weight SMALLINT NOT NULL, CHANGE grip_size grip_size SMALLINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE racquet_ordered CHANGE head_size head_size INT DEFAULT NULL, CHANGE string_pattern string_pattern VARCHAR(5) DEFAULT NULL, CHANGE weight weight INT DEFAULT NULL, CHANGE grip_size grip_size SMALLINT DEFAULT NULL');
    }
}
