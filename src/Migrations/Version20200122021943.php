<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200122021943 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE UNIQUE INDEX UNIQ_2E19B9473A909126 ON causa_condicion (nombre)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_21E3F5EC3A909126 ON situacion (nombre)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1FBB15AC3A909126 ON tipo_accion (nombre)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_2E19B9473A909126 ON causa_condicion');
        $this->addSql('DROP INDEX UNIQ_21E3F5EC3A909126 ON situacion');
        $this->addSql('DROP INDEX UNIQ_1FBB15AC3A909126 ON tipo_accion');
    }
}
