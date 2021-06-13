<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200329152120 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE accion_control (id INT AUTO_INCREMENT NOT NULL, entidad_id INT NOT NULL, tipo_accion_id INT NOT NULL, particularidad_id INT NOT NULL, combustible_id INT DEFAULT NULL, orden_trabajo VARCHAR(7) NOT NULL, directivas VARCHAR(50) DEFAULT NULL, auditor_plan SMALLINT DEFAULT NULL, auditor_real SMALLINT DEFAULT NULL, dias_plan SMALLINT DEFAULT NULL, dias_real SMALLINT DEFAULT NULL, auditor_xdia_plan SMALLINT DEFAULT NULL, auditor_xdia_real SMALLINT DEFAULT NULL, fecha_inicio_plan DATE NOT NULL, fecha_fin_plan DATE NOT NULL, fecha_inicio_real DATE NOT NULL, fecha_fin_real DATE NOT NULL, calificacion VARCHAR(7) DEFAULT NULL, dano_cup DOUBLE PRECISION DEFAULT NULL, dano_cuc DOUBLE PRECISION DEFAULT NULL, dano_otra_moneda DOUBLE PRECISION DEFAULT NULL, plan_medidas TINYINT(1) NOT NULL, activo TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_4FF9A0D96CA204EF (entidad_id), UNIQUE INDEX UNIQ_4FF9A0D9DD25ED3B (tipo_accion_id), UNIQUE INDEX UNIQ_4FF9A0D9545F6541 (particularidad_id), UNIQUE INDEX UNIQ_4FF9A0D9D5BD96DF (combustible_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE accion_control ADD CONSTRAINT FK_4FF9A0D96CA204EF FOREIGN KEY (entidad_id) REFERENCES entidad (id)');
        $this->addSql('ALTER TABLE accion_control ADD CONSTRAINT FK_4FF9A0D9DD25ED3B FOREIGN KEY (tipo_accion_id) REFERENCES tipo_accion (id)');
        $this->addSql('ALTER TABLE accion_control ADD CONSTRAINT FK_4FF9A0D9545F6541 FOREIGN KEY (particularidad_id) REFERENCES particularidad (id)');
        $this->addSql('ALTER TABLE accion_control ADD CONSTRAINT FK_4FF9A0D9D5BD96DF FOREIGN KEY (combustible_id) REFERENCES combustible (id)');
        $this->addSql('ALTER TABLE hc ADD accion_control_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hc ADD CONSTRAINT FK_3846C3B28E7F3D91 FOREIGN KEY (accion_control_id) REFERENCES accion_control (id)');
        $this->addSql('CREATE INDEX IDX_3846C3B28E7F3D91 ON hc (accion_control_id)');
        $this->addSql('ALTER TABLE phc ADD accion_control_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE phc ADD CONSTRAINT FK_D22F600F8E7F3D91 FOREIGN KEY (accion_control_id) REFERENCES accion_control (id)');
        $this->addSql('CREATE INDEX IDX_D22F600F8E7F3D91 ON phc (accion_control_id)');
        $this->addSql('ALTER TABLE phd ADD accion_control_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE phd ADD CONSTRAINT FK_4C4BF5AC8E7F3D91 FOREIGN KEY (accion_control_id) REFERENCES accion_control (id)');
        $this->addSql('CREATE INDEX IDX_4C4BF5AC8E7F3D91 ON phd (accion_control_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hc DROP FOREIGN KEY FK_3846C3B28E7F3D91');
        $this->addSql('ALTER TABLE phc DROP FOREIGN KEY FK_D22F600F8E7F3D91');
        $this->addSql('ALTER TABLE phd DROP FOREIGN KEY FK_4C4BF5AC8E7F3D91');
        $this->addSql('DROP TABLE accion_control');
        $this->addSql('DROP INDEX IDX_3846C3B28E7F3D91 ON hc');
        $this->addSql('ALTER TABLE hc DROP accion_control_id');
        $this->addSql('DROP INDEX IDX_D22F600F8E7F3D91 ON phc');
        $this->addSql('ALTER TABLE phc DROP accion_control_id');
        $this->addSql('DROP INDEX IDX_4C4BF5AC8E7F3D91 ON phd');
        $this->addSql('ALTER TABLE phd DROP accion_control_id');
    }
}
