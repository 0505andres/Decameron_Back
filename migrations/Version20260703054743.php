<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260703054743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8E86059E3A909126 ON ciudad (nombre)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_512E2E483A909126 ON tipo_acomodacion (nombre)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_65DF97923A909126 ON tipo_habitacion (nombre)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_8E86059E3A909126');
        $this->addSql('DROP INDEX UNIQ_512E2E483A909126');
        $this->addSql('DROP INDEX UNIQ_65DF97923A909126');
    }
}
