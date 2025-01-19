<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250119110933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subdomain_record (id INT AUTO_INCREMENT NOT NULL, subdomain_id INT NOT NULL, type VARCHAR(40) NOT NULL, value VARCHAR(500) NOT NULL, ttl INT NOT NULL, priority INT DEFAULT NULL, INDEX IDX_B95EB6C78530A5DC (subdomain_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subdomain_record ADD CONSTRAINT FK_B95EB6C78530A5DC FOREIGN KEY (subdomain_id) REFERENCES subdomain (id)');
        $this->addSql('ALTER TABLE subdomain DROP record, DROP value, DROP ttl, DROP priority');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subdomain_record DROP FOREIGN KEY FK_B95EB6C78530A5DC');
        $this->addSql('DROP TABLE subdomain_record');
        $this->addSql('ALTER TABLE subdomain ADD record VARCHAR(40) NOT NULL, ADD value VARCHAR(500) NOT NULL, ADD ttl INT NOT NULL, ADD priority INT NOT NULL');
    }
}
