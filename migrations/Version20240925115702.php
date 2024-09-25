<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240925115702 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add foreign key relationships to associate users with assets and transactions.';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE asset ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE asset ADD CONSTRAINT FK_2AF5A5CA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_2AF5A5CA76ED395 ON asset (user_id)');
        $this->addSql('ALTER TABLE transaction ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D1A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_723705D1A76ED395 ON transaction (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE transaction DROP CONSTRAINT FK_723705D1A76ED395');
        $this->addSql('DROP INDEX IDX_723705D1A76ED395');
        $this->addSql('ALTER TABLE transaction DROP user_id');
        $this->addSql('ALTER TABLE asset DROP CONSTRAINT FK_2AF5A5CA76ED395');
        $this->addSql('DROP INDEX IDX_2AF5A5CA76ED395');
        $this->addSql('ALTER TABLE asset DROP user_id');
    }
}
