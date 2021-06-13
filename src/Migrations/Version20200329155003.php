<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200329155003 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accion_control DROP INDEX UNIQ_4FF9A0D9DD25ED3B, ADD INDEX IDX_4FF9A0D9DD25ED3B (tipo_accion_id)');
        $this->addSql('ALTER TABLE accion_control DROP INDEX UNIQ_4FF9A0D9545F6541, ADD INDEX IDX_4FF9A0D9545F6541 (particularidad_id)');
        $this->addSql('ALTER TABLE accion_control DROP INDEX UNIQ_4FF9A0D96CA204EF, ADD INDEX IDX_4FF9A0D96CA204EF (entidad_id)');
        $this->addSql('ALTER TABLE accion_control CHANGE entidad_id entidad_id INT DEFAULT NULL, CHANGE tipo_accion_id tipo_accion_id INT DEFAULT NULL, CHANGE particularidad_id particularidad_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accion_control DROP INDEX IDX_4FF9A0D96CA204EF, ADD UNIQUE INDEX UNIQ_4FF9A0D96CA204EF (entidad_id)');
        $this->addSql('ALTER TABLE accion_control DROP INDEX IDX_4FF9A0D9DD25ED3B, ADD UNIQUE INDEX UNIQ_4FF9A0D9DD25ED3B (tipo_accion_id)');
        $this->addSql('ALTER TABLE accion_control DROP INDEX IDX_4FF9A0D9545F6541, ADD UNIQUE INDEX UNIQ_4FF9A0D9545F6541 (particularidad_id)');
        $this->addSql('ALTER TABLE accion_control CHANGE entidad_id entidad_id INT NOT NULL, CHANGE tipo_accion_id tipo_accion_id INT NOT NULL, CHANGE particularidad_id particularidad_id INT NOT NULL');
    }
}
