<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250206135605 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add sell_strategy table.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE sell_strategy_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE sell_strategy (id INT NOT NULL, asset_id INT NOT NULL, percentage DOUBLE PRECISION NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F521B9F25DA1941 ON sell_strategy (asset_id)');
        $this->addSql('ALTER TABLE sell_strategy ADD CONSTRAINT FK_F521B9F25DA1941 FOREIGN KEY (asset_id) REFERENCES asset (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE sell_strategy_id_seq CASCADE');
        $this->addSql('ALTER TABLE sell_strategy DROP CONSTRAINT FK_F521B9F25DA1941');
        $this->addSql('DROP TABLE sell_strategy');
    }
}
