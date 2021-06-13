<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200115183631 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE localidad (id INT AUTO_INCREMENT NOT NULL, municipio_id INT NOT NULL, nombre VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_4F68E0103A909126 (nombre), INDEX IDX_4F68E01058BC1BE0 (municipio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE municipio (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(40) NOT NULL, UNIQUE INDEX UNIQ_FE98F5E03A909126 (nombre), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE localidad ADD CONSTRAINT FK_4F68E01058BC1BE0 FOREIGN KEY (municipio_id) REFERENCES municipio (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE localidad DROP FOREIGN KEY FK_4F68E01058BC1BE0');
        $this->addSql('DROP TABLE localidad');
        $this->addSql('DROP TABLE municipio');
    }
}
