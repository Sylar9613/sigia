<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200315234050 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE entidad ADD activo TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE cargo ADD activo TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE localidad ADD activo TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE municipio ADD activo TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE organismo ADD activo TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE osde ADD activo TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE plaza ADD activo TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE cargo DROP activo');
        $this->addSql('ALTER TABLE entidad DROP activo');
        $this->addSql('ALTER TABLE localidad DROP activo');
        $this->addSql('ALTER TABLE municipio DROP activo');
        $this->addSql('ALTER TABLE organismo DROP activo');
        $this->addSql('ALTER TABLE osde DROP activo');
        $this->addSql('ALTER TABLE plaza DROP activo');
    }
}
