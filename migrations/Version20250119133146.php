<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250119133146 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subdomain ADD domain_id INT NOT NULL');
        $this->addSql('ALTER TABLE subdomain ADD CONSTRAINT FK_C1D5962E115F0EE5 FOREIGN KEY (domain_id) REFERENCES domain (id)');
        $this->addSql('CREATE INDEX IDX_C1D5962E115F0EE5 ON subdomain (domain_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subdomain DROP FOREIGN KEY FK_C1D5962E115F0EE5');
        $this->addSql('DROP INDEX IDX_C1D5962E115F0EE5 ON subdomain');
        $this->addSql('ALTER TABLE subdomain DROP domain_id');
    }
}
