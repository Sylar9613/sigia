<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200404201326 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hc DROP FOREIGN KEY FK_3846C3B28E7F3D91');
        $this->addSql('DROP INDEX IDX_3846C3B28E7F3D91 ON hc');
        $this->addSql('ALTER TABLE hc DROP accion_control_id');
        $this->addSql('ALTER TABLE phc ADD hc_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE phc ADD CONSTRAINT FK_D22F600FDFF38F5E FOREIGN KEY (hc_id) REFERENCES hc (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D22F600FDFF38F5E ON phc (hc_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hc ADD accion_control_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hc ADD CONSTRAINT FK_3846C3B28E7F3D91 FOREIGN KEY (accion_control_id) REFERENCES accion_control (id)');
        $this->addSql('CREATE INDEX IDX_3846C3B28E7F3D91 ON hc (accion_control_id)');
        $this->addSql('ALTER TABLE phc DROP FOREIGN KEY FK_D22F600FDFF38F5E');
        $this->addSql('DROP INDEX UNIQ_D22F600FDFF38F5E ON phc');
        $this->addSql('ALTER TABLE phc DROP hc_id');
    }
}
