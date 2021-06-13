<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200317205148 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE hc (id INT AUTO_INCREMENT NOT NULL, entidad_id INT NOT NULL, municipio_id INT NOT NULL, numero_expediente INT NOT NULL, categoria VARCHAR(50) NOT NULL, provincia VARCHAR(50) NOT NULL, fuente VARCHAR(50) NOT NULL, fecha_deteccion DATE NOT NULL, fecha_ocurrencia DATE NOT NULL, resumen LONGTEXT DEFAULT NULL, objeto_social_entidad LONGTEXT DEFAULT NULL, total_implicados_entidad SMALLINT DEFAULT NULL, total_implicados_otras SMALLINT DEFAULT NULL, afectacion_economica_cup DOUBLE PRECISION DEFAULT NULL, recuperado_cup DOUBLE PRECISION DEFAULT NULL, activo TINYINT(1) NOT NULL, INDEX IDX_3846C3B26CA204EF (entidad_id), INDEX IDX_3846C3B258BC1BE0 (municipio_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hc ADD CONSTRAINT FK_3846C3B26CA204EF FOREIGN KEY (entidad_id) REFERENCES entidad (id)');
        $this->addSql('ALTER TABLE hc ADD CONSTRAINT FK_3846C3B258BC1BE0 FOREIGN KEY (municipio_id) REFERENCES municipio (id)');
        $this->addSql('ALTER TABLE responsabilidad ADD h_c_id INT DEFAULT NULL, ADD medidas_total SMALLINT DEFAULT NULL, ADD medidas_pendientes SMALLINT DEFAULT NULL');
        $this->addSql('ALTER TABLE responsabilidad ADD CONSTRAINT FK_DBB7ACE85FB53041 FOREIGN KEY (h_c_id) REFERENCES hc (id)');
        $this->addSql('CREATE INDEX IDX_DBB7ACE85FB53041 ON responsabilidad (h_c_id)');
        $this->addSql('ALTER TABLE implicado ADD h_c_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE implicado ADD CONSTRAINT FK_E641B79D5FB53041 FOREIGN KEY (h_c_id) REFERENCES hc (id)');
        $this->addSql('CREATE INDEX IDX_E641B79D5FB53041 ON implicado (h_c_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE responsabilidad DROP FOREIGN KEY FK_DBB7ACE85FB53041');
        $this->addSql('ALTER TABLE implicado DROP FOREIGN KEY FK_E641B79D5FB53041');
        $this->addSql('DROP TABLE hc');
        $this->addSql('DROP INDEX IDX_E641B79D5FB53041 ON implicado');
        $this->addSql('ALTER TABLE implicado DROP h_c_id');
        $this->addSql('DROP INDEX IDX_DBB7ACE85FB53041 ON responsabilidad');
        $this->addSql('ALTER TABLE responsabilidad DROP h_c_id, DROP medidas_total, DROP medidas_pendientes');
    }
}
