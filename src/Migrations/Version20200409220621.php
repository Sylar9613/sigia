<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200409220621 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hc ADD entidad_id INT NOT NULL, ADD municipio_id INT NOT NULL');
        $this->addSql('ALTER TABLE hc ADD CONSTRAINT FK_3846C3B26CA204EF FOREIGN KEY (entidad_id) REFERENCES entidad (id)');
        $this->addSql('ALTER TABLE hc ADD CONSTRAINT FK_3846C3B258BC1BE0 FOREIGN KEY (municipio_id) REFERENCES municipio (id)');
        $this->addSql('CREATE INDEX IDX_3846C3B26CA204EF ON hc (entidad_id)');
        $this->addSql('CREATE INDEX IDX_3846C3B258BC1BE0 ON hc (municipio_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE hc DROP FOREIGN KEY FK_3846C3B26CA204EF');
        $this->addSql('ALTER TABLE hc DROP FOREIGN KEY FK_3846C3B258BC1BE0');
        $this->addSql('DROP INDEX IDX_3846C3B26CA204EF ON hc');
        $this->addSql('DROP INDEX IDX_3846C3B258BC1BE0 ON hc');
        $this->addSql('ALTER TABLE hc DROP entidad_id, DROP municipio_id');
    }
}
