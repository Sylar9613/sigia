<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200323000334 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE implicado DROP FOREIGN KEY FK_E641B79D96079139');
        $this->addSql('DROP INDEX IDX_E641B79D96079139 ON implicado');
        $this->addSql('ALTER TABLE implicado DROP responsabilidad_id');
        $this->addSql('ALTER TABLE medida_disciplinaria DROP FOREIGN KEY FK_3F2741B696079139');
        $this->addSql('DROP INDEX IDX_3F2741B696079139 ON medida_disciplinaria');
        $this->addSql('ALTER TABLE medida_disciplinaria DROP responsabilidad_id');
        $this->addSql('ALTER TABLE responsabilidad ADD medida_disciplinaria_id INT NOT NULL, ADD implicado_id INT NOT NULL');
        $this->addSql('ALTER TABLE responsabilidad ADD CONSTRAINT FK_DBB7ACE8EBFA8C82 FOREIGN KEY (medida_disciplinaria_id) REFERENCES medida_disciplinaria (id)');
        $this->addSql('ALTER TABLE responsabilidad ADD CONSTRAINT FK_DBB7ACE839D5A70C FOREIGN KEY (implicado_id) REFERENCES implicado (id)');
        $this->addSql('CREATE INDEX IDX_DBB7ACE8EBFA8C82 ON responsabilidad (medida_disciplinaria_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_DBB7ACE839D5A70C ON responsabilidad (implicado_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE implicado ADD responsabilidad_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE implicado ADD CONSTRAINT FK_E641B79D96079139 FOREIGN KEY (responsabilidad_id) REFERENCES responsabilidad (id)');
        $this->addSql('CREATE INDEX IDX_E641B79D96079139 ON implicado (responsabilidad_id)');
        $this->addSql('ALTER TABLE medida_disciplinaria ADD responsabilidad_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE medida_disciplinaria ADD CONSTRAINT FK_3F2741B696079139 FOREIGN KEY (responsabilidad_id) REFERENCES responsabilidad (id)');
        $this->addSql('CREATE INDEX IDX_3F2741B696079139 ON medida_disciplinaria (responsabilidad_id)');
        $this->addSql('ALTER TABLE responsabilidad DROP FOREIGN KEY FK_DBB7ACE8EBFA8C82');
        $this->addSql('ALTER TABLE responsabilidad DROP FOREIGN KEY FK_DBB7ACE839D5A70C');
        $this->addSql('DROP INDEX IDX_DBB7ACE8EBFA8C82 ON responsabilidad');
        $this->addSql('DROP INDEX UNIQ_DBB7ACE839D5A70C ON responsabilidad');
        $this->addSql('ALTER TABLE responsabilidad DROP medida_disciplinaria_id, DROP implicado_id');
    }
}
