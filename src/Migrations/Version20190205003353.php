<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190205003353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            <<<'SQL'
ALTER TABLE product
  ADD updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)'
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE product DROP updated_at');
    }
}
