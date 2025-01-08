<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241216134908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add current_price to cryptocurency table. Remove price from transaction table.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cryptocurrency ADD current_price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction DROP price');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cryptocurrency DROP current_price');
        $this->addSql('ALTER TABLE transaction ADD price DOUBLE PRECISION DEFAULT NULL');
    }
}
