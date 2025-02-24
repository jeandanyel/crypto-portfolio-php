<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250224141259 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add average_buy_price and total_invested to Asset table.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE asset ADD average_buy_price DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE asset ADD total_invested DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE asset DROP average_buy_price');
        $this->addSql('ALTER TABLE asset DROP total_invested');
    }
}
