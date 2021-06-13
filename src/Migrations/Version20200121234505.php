<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200121234505 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE causa_condicion (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(80) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE implicado (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(200) NOT NULL, cargo VARCHAR(200) NOT NULL, categoria_ocupacional VARCHAR(5) NOT NULL, escolaridad VARCHAR(5) NOT NULL, pcc TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE phd (id INT AUTO_INCREMENT NOT NULL, entidad_id INT NOT NULL, unidad_organizativa VARCHAR(255) NOT NULL, orden_trabajo VARCHAR(7) NOT NULL, INDEX IDX_4C4BF5AC6CA204EF (entidad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE situacion (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(80) NOT NULL, fecha DATE DEFAULT NULL, emisor VARCHAR(30) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tipo_accion (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(40) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE phd ADD CONSTRAINT FK_4C4BF5AC6CA204EF FOREIGN KEY (entidad_id) REFERENCES entidad (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE causa_condicion');
        $this->addSql('DROP TABLE implicado');
        $this->addSql('DROP TABLE phd');
        $this->addSql('DROP TABLE situacion');
        $this->addSql('DROP TABLE tipo_accion');
    }
}
