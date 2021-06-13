<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200122002252 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE implicado ADD phd_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE implicado ADD CONSTRAINT FK_E641B79D3EE6EC98 FOREIGN KEY (phd_id) REFERENCES phd (id)');
        $this->addSql('CREATE INDEX IDX_E641B79D3EE6EC98 ON implicado (phd_id)');
        $this->addSql('ALTER TABLE phd ADD tipo_accion_id INT DEFAULT NULL, ADD situacion_id INT NOT NULL, ADD causa_condicion_id INT DEFAULT NULL, ADD fecha DATE NOT NULL, ADD numero_expediente INT DEFAULT NULL, ADD numero_causa INT DEFAULT NULL, ADD dano_economico_cup DOUBLE PRECISION DEFAULT NULL, ADD dano_economico_otra_moneda DOUBLE PRECISION DEFAULT NULL, ADD sintesis LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE phd ADD CONSTRAINT FK_4C4BF5ACDD25ED3B FOREIGN KEY (tipo_accion_id) REFERENCES tipo_accion (id)');
        $this->addSql('ALTER TABLE phd ADD CONSTRAINT FK_4C4BF5AC96714AEF FOREIGN KEY (situacion_id) REFERENCES situacion (id)');
        $this->addSql('ALTER TABLE phd ADD CONSTRAINT FK_4C4BF5AC42F8E36A FOREIGN KEY (causa_condicion_id) REFERENCES causa_condicion (id)');
        $this->addSql('CREATE INDEX IDX_4C4BF5ACDD25ED3B ON phd (tipo_accion_id)');
        $this->addSql('CREATE INDEX IDX_4C4BF5AC96714AEF ON phd (situacion_id)');
        $this->addSql('CREATE INDEX IDX_4C4BF5AC42F8E36A ON phd (causa_condicion_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE implicado DROP FOREIGN KEY FK_E641B79D3EE6EC98');
        $this->addSql('DROP INDEX IDX_E641B79D3EE6EC98 ON implicado');
        $this->addSql('ALTER TABLE implicado DROP phd_id');
        $this->addSql('ALTER TABLE phd DROP FOREIGN KEY FK_4C4BF5ACDD25ED3B');
        $this->addSql('ALTER TABLE phd DROP FOREIGN KEY FK_4C4BF5AC96714AEF');
        $this->addSql('ALTER TABLE phd DROP FOREIGN KEY FK_4C4BF5AC42F8E36A');
        $this->addSql('DROP INDEX IDX_4C4BF5ACDD25ED3B ON phd');
        $this->addSql('DROP INDEX IDX_4C4BF5AC96714AEF ON phd');
        $this->addSql('DROP INDEX IDX_4C4BF5AC42F8E36A ON phd');
        $this->addSql('ALTER TABLE phd DROP tipo_accion_id, DROP situacion_id, DROP causa_condicion_id, DROP fecha, DROP numero_expediente, DROP numero_causa, DROP dano_economico_cup, DROP dano_economico_otra_moneda, DROP sintesis');
    }
}
