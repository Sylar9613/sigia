<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200409225748 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE product');
        $this->addSql('ALTER TABLE hc DROP FOREIGN KEY FK_3846C3B258BC1BE0');
        $this->addSql('ALTER TABLE hc DROP FOREIGN KEY FK_3846C3B26CA204EF');
        $this->addSql('DROP INDEX IDX_3846C3B258BC1BE0 ON hc');
        $this->addSql('DROP INDEX IDX_3846C3B26CA204EF ON hc');
        $this->addSql('ALTER TABLE hc DROP entidad_id, DROP municipio_id');
        $this->addSql('ALTER TABLE accion_control CHANGE orden_trabajo orden_trabajo VARCHAR(10) NOT NULL, CHANGE calificacion calificacion VARCHAR(10) DEFAULT NULL');
        $this->addSql('ALTER TABLE auditor CHANGE rna rna VARCHAR(10) NOT NULL');
        $this->addSql('ALTER TABLE implicado CHANGE categoria_ocupacional categoria_ocupacional VARCHAR(50) NOT NULL, CHANGE escolaridad escolaridad VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE product (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE latin1_swedish_ci, description LONGTEXT NOT NULL COLLATE latin1_swedish_ci, brochure_filename VARCHAR(255) NOT NULL COLLATE latin1_swedish_ci, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE accion_control CHANGE orden_trabajo orden_trabajo VARCHAR(7) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE calificacion calificacion VARCHAR(7) DEFAULT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE auditor CHANGE rna rna VARCHAR(6) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE hc ADD entidad_id INT NOT NULL, ADD municipio_id INT NOT NULL');
        $this->addSql('ALTER TABLE hc ADD CONSTRAINT FK_3846C3B258BC1BE0 FOREIGN KEY (municipio_id) REFERENCES municipio (id)');
        $this->addSql('ALTER TABLE hc ADD CONSTRAINT FK_3846C3B26CA204EF FOREIGN KEY (entidad_id) REFERENCES entidad (id)');
        $this->addSql('CREATE INDEX IDX_3846C3B258BC1BE0 ON hc (municipio_id)');
        $this->addSql('CREATE INDEX IDX_3846C3B26CA204EF ON hc (entidad_id)');
        $this->addSql('ALTER TABLE implicado CHANGE categoria_ocupacional categoria_ocupacional VARCHAR(5) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE escolaridad escolaridad VARCHAR(5) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
