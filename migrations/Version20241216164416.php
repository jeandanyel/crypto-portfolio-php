<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241216164416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add received_asset_price and transacted_asset_price columns to transaction table.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE transaction ADD received_asset_price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE transaction ADD transacted_asset_price DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE transaction DROP received_asset_price');
        $this->addSql('ALTER TABLE transaction DROP transacted_asset_price');
    }
}
