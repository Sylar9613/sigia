<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200116173127 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE entidad (id INT AUTO_INCREMENT NOT NULL, osde_id INT NOT NULL, nombre VARCHAR(70) NOT NULL, ai TINYINT(1) NOT NULL, nit VARCHAR(11) NOT NULL, reeup VARCHAR(11) NOT NULL, uai TINYINT(1) NOT NULL, ucai TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_4587B0CB3A909126 (nombre), UNIQUE INDEX UNIQ_4587B0CB5E5F5AF3 (nit), UNIQUE INDEX UNIQ_4587B0CB599B6C7 (reeup), INDEX IDX_4587B0CB946E5683 (osde_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE organismo (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(70) NOT NULL, controlador TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_3DDAAC2D3A909126 (nombre), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE osde (id INT AUTO_INCREMENT NOT NULL, organismo_id INT NOT NULL, nombre VARCHAR(70) NOT NULL, UNIQUE INDEX UNIQ_28FFD5343A909126 (nombre), INDEX IDX_28FFD5343260D891 (organismo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entidad ADD CONSTRAINT FK_4587B0CB946E5683 FOREIGN KEY (osde_id) REFERENCES osde (id)');
        $this->addSql('ALTER TABLE osde ADD CONSTRAINT FK_28FFD5343260D891 FOREIGN KEY (organismo_id) REFERENCES organismo (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE osde DROP FOREIGN KEY FK_28FFD5343260D891');
        $this->addSql('ALTER TABLE entidad DROP FOREIGN KEY FK_4587B0CB946E5683');
        $this->addSql('DROP TABLE entidad');
        $this->addSql('DROP TABLE organismo');
        $this->addSql('DROP TABLE osde');
    }
}
