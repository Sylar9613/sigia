<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200116235152 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE cargo (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, es_contralor TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_3BEE57713A909126 (nombre), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nivel (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(100) NOT NULL, UNIQUE INDEX UNIQ_AAFC20CB3A909126 (nombre), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plaza (id INT AUTO_INCREMENT NOT NULL, entidad_id INT NOT NULL, cargo_id INT NOT NULL, plazas INT NOT NULL, INDEX IDX_E8703ECC6CA204EF (entidad_id), INDEX IDX_E8703ECC813AC380 (cargo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE plaza ADD CONSTRAINT FK_E8703ECC6CA204EF FOREIGN KEY (entidad_id) REFERENCES entidad (id)');
        $this->addSql('ALTER TABLE plaza ADD CONSTRAINT FK_E8703ECC813AC380 FOREIGN KEY (cargo_id) REFERENCES cargo (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE plaza DROP FOREIGN KEY FK_E8703ECC813AC380');
        $this->addSql('DROP TABLE cargo');
        $this->addSql('DROP TABLE nivel');
        $this->addSql('DROP TABLE plaza');
    }
}
