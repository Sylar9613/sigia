<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200317011252 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE phc (id INT AUTO_INCREMENT NOT NULL, entidad_id INT NOT NULL, municipio_id INT NOT NULL, numero INT NOT NULL, categoria VARCHAR(50) NOT NULL, provincia VARCHAR(50) NOT NULL, fuente VARCHAR(50) NOT NULL, fecha_deteccion DATE NOT NULL, fecha_ocurrencia DATE NOT NULL, resumen LONGTEXT DEFAULT NULL, INDEX IDX_D22F600F6CA204EF (entidad_id), INDEX IDX_D22F600F58BC1BE0 (municipio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE phc ADD CONSTRAINT FK_D22F600F6CA204EF FOREIGN KEY (entidad_id) REFERENCES entidad (id)');
        $this->addSql('ALTER TABLE phc ADD CONSTRAINT FK_D22F600F58BC1BE0 FOREIGN KEY (municipio_id) REFERENCES municipio (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE phc');
    }
}
