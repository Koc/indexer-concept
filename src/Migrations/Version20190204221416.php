<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20190204221416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(
            <<<'SQL'
CREATE TABLE product
(
  id   INT AUTO_INCREMENT NOT NULL,
  name VARCHAR(255)       NOT NULL,
  PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB
SQL
        );

        $this->addSql(
            <<<'SQL'
CREATE TABLE offer
(
  id           INT AUTO_INCREMENT NOT NULL,
  product_id   INT DEFAULT NULL,
  price        INT                NOT NULL,
  company_name VARCHAR(255)       NOT NULL,
  INDEX IDX_29D6873E4584665A (product_id),
  PRIMARY KEY (id)
) DEFAULT CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci
  ENGINE = InnoDB
SQL
        );

        $this->addSql(
            <<<'SQL'
ALTER TABLE offer
  ADD CONSTRAINT FK_29D6873E4584665A FOREIGN KEY (product_id) REFERENCES product (id)
SQL
        );
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE offer DROP FOREIGN KEY FK_29D6873E4584665A');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE offer');
    }
}
