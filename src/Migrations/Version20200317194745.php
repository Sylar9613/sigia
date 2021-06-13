<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200317194745 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE causa_condicion ADD activo TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE combustible ADD activo TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE implicado ADD activo TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE medida_disciplinaria ADD activo TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE particularidad ADD activo TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE phc ADD activo TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE phd ADD activo TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE responsabilidad ADD activo TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE situacion ADD activo TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE tipo_accion ADD activo TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE causa_condicion DROP activo');
        $this->addSql('ALTER TABLE combustible DROP activo');
        $this->addSql('ALTER TABLE implicado DROP activo');
        $this->addSql('ALTER TABLE medida_disciplinaria DROP activo');
        $this->addSql('ALTER TABLE particularidad DROP activo');
        $this->addSql('ALTER TABLE phc DROP activo');
        $this->addSql('ALTER TABLE phd DROP activo');
        $this->addSql('ALTER TABLE responsabilidad DROP activo');
        $this->addSql('ALTER TABLE situacion DROP activo');
        $this->addSql('ALTER TABLE tipo_accion DROP activo');
    }
}
