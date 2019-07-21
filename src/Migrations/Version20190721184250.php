<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190721184250 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite',
            'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE uri (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, original_url CLOB NOT NULL, short_code VARCHAR(8) NOT NULL, url_hash VARCHAR(32) NOT NULL)');
        $this->addSql('CREATE INDEX create_idx ON uri (url_hash)');
        $this->addSql('CREATE INDEX read_idx ON uri (short_code)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite',
            'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE uri');
    }
}
