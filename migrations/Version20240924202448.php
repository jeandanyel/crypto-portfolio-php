<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240924202448 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add cryptocurrency, asset and transaction table.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE asset_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE cryptocurrency_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE transaction_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE asset (id INT NOT NULL, cryptocurrency_id INT NOT NULL, quantity DOUBLE PRECISION NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2AF5A5C583FC03A ON asset (cryptocurrency_id)');
        $this->addSql('COMMENT ON COLUMN asset.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN asset.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE cryptocurrency (id INT NOT NULL, name VARCHAR(255) NOT NULL, symbol VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN cryptocurrency.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN cryptocurrency.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE transaction (id INT NOT NULL, transacted_asset_id INT DEFAULT NULL, received_asset_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, fee DOUBLE PRECISION DEFAULT NULL, notes TEXT DEFAULT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, transacted_quantity DOUBLE PRECISION DEFAULT NULL, received_quantity DOUBLE PRECISION DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_723705D1CC88797F ON transaction (transacted_asset_id)');
        $this->addSql('CREATE INDEX IDX_723705D117CF131B ON transaction (received_asset_id)');
        $this->addSql('COMMENT ON COLUMN transaction.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN transaction.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN transaction.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE asset ADD CONSTRAINT FK_2AF5A5C583FC03A FOREIGN KEY (cryptocurrency_id) REFERENCES cryptocurrency (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1CC88797F FOREIGN KEY (transacted_asset_id) REFERENCES asset (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D117CF131B FOREIGN KEY (received_asset_id) REFERENCES asset (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE asset_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE cryptocurrency_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE transaction_id_seq CASCADE');
        $this->addSql('ALTER TABLE asset DROP CONSTRAINT FK_2AF5A5C583FC03A');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1CC88797F');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D117CF131B');
        $this->addSql('DROP TABLE asset');
        $this->addSql('DROP TABLE cryptocurrency');
        $this->addSql('DROP TABLE transaction');
    }
}
