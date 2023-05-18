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
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql(
            'CREATE TABLE `uri` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `original_url` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
                    `short_code` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL,
                    `url_hash` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
                    PRIMARY KEY (`id`),
                    KEY `create_idx` (`url_hash`),
                    KEY `read_idx` (`short_code`)
                ) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf(
            $this->connection->getDatabasePlatform()->getName() !== 'mysql',
            'Migration can only be executed safely on \'mysql\'.'
        );

        $this->addSql('DROP TABLE uri');
    }
}
