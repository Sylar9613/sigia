<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200117040629 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE auditor (id INT AUTO_INCREMENT NOT NULL, localidad_id INT NOT NULL, entidad_id INT NOT NULL, cargo_id INT NOT NULL, nivel_id INT NOT NULL, imagen VARCHAR(255) NOT NULL, activo TINYINT(1) NOT NULL, nombres VARCHAR(60) NOT NULL, apellidos VARCHAR(60) NOT NULL, ci VARCHAR(11) NOT NULL, direccion LONGTEXT NOT NULL, telefono VARCHAR(20) NOT NULL, correo VARCHAR(30) NOT NULL, fea TINYINT(1) NOT NULL, rna VARCHAR(6) NOT NULL, fecha_rna DATE NOT NULL, UNIQUE INDEX UNIQ_CE48CAAD3B67F367 (ci), UNIQUE INDEX UNIQ_CE48CAAD69FF72CB (rna), INDEX IDX_CE48CAAD67707C89 (localidad_id), INDEX IDX_CE48CAAD6CA204EF (entidad_id), INDEX IDX_CE48CAAD813AC380 (cargo_id), INDEX IDX_CE48CAADDA3426AE (nivel_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE auditor ADD CONSTRAINT FK_CE48CAAD67707C89 FOREIGN KEY (localidad_id) REFERENCES localidad (id)');
        $this->addSql('ALTER TABLE auditor ADD CONSTRAINT FK_CE48CAAD6CA204EF FOREIGN KEY (entidad_id) REFERENCES entidad (id)');
        $this->addSql('ALTER TABLE auditor ADD CONSTRAINT FK_CE48CAAD813AC380 FOREIGN KEY (cargo_id) REFERENCES cargo (id)');
        $this->addSql('ALTER TABLE auditor ADD CONSTRAINT FK_CE48CAADDA3426AE FOREIGN KEY (nivel_id) REFERENCES nivel (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE auditor');
    }
}
