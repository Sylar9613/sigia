<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200317004356 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE medida_disciplinaria (id INT AUTO_INCREMENT NOT NULL, responsabilidad_id INT DEFAULT NULL, categoria VARCHAR(40) NOT NULL, nombre VARCHAR(50) NOT NULL, INDEX IDX_3F2741B696079139 (responsabilidad_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE responsabilidad (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE medida_disciplinaria ADD CONSTRAINT FK_3F2741B696079139 FOREIGN KEY (responsabilidad_id) REFERENCES responsabilidad (id)');
        $this->addSql('ALTER TABLE implicado ADD responsabilidad_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE implicado ADD CONSTRAINT FK_E641B79D96079139 FOREIGN KEY (responsabilidad_id) REFERENCES responsabilidad (id)');
        $this->addSql('CREATE INDEX IDX_E641B79D96079139 ON implicado (responsabilidad_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE implicado DROP FOREIGN KEY FK_E641B79D96079139');
        $this->addSql('ALTER TABLE medida_disciplinaria DROP FOREIGN KEY FK_3F2741B696079139');
        $this->addSql('DROP TABLE medida_disciplinaria');
        $this->addSql('DROP TABLE responsabilidad');
        $this->addSql('DROP INDEX IDX_E641B79D96079139 ON implicado');
        $this->addSql('ALTER TABLE implicado DROP responsabilidad_id');
    }
}
