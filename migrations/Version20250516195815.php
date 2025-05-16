<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
final class Version20250516195815 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE TABLE client (id SERIAL NOT NULL, testimony_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(170) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_C7440455B879FBFE ON client (testimony_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE livrable (id SERIAL NOT NULL, projet_id INT NOT NULL, name VARCHAR(255) NOT NULL, goal TEXT NOT NULL, goal_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_9E78008CC18272 ON livrable (projet_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE projet (id SERIAL NOT NULL, client_id INT NOT NULL, name VARCHAR(255) NOT NULL, description TEXT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_50159CA919EB6921 ON projet (client_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE testimony (id SERIAL NOT NULL, content TEXT NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE "user" (id SERIAL NOT NULL, email VARCHAR(180) NOT NULL, first_name VARCHAR(50) NOT NULL, last_name VARCHAR(50) NOT NULL, token VARCHAR(255) NOT NULL, reset_expiration_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON "user" (email)
        SQL);
        $this->addSql(<<<'SQL'
            COMMENT ON COLUMN "user".created_at IS '(DC2Type:datetime_immutable)'
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE client ADD CONSTRAINT FK_C7440455B879FBFE FOREIGN KEY (testimony_id) REFERENCES testimony (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livrable ADD CONSTRAINT FK_9E78008CC18272 FOREIGN KEY (projet_id) REFERENCES projet (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet ADD CONSTRAINT FK_50159CA919EB6921 FOREIGN KEY (client_id) REFERENCES client (id) NOT DEFERRABLE INITIALLY IMMEDIATE
        SQL);
    }

    public function down(Schema $schema): void
    {
        $this->addSql(<<<'SQL'
            CREATE SCHEMA public
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE client DROP CONSTRAINT FK_C7440455B879FBFE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE livrable DROP CONSTRAINT FK_9E78008CC18272
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE projet DROP CONSTRAINT FK_50159CA919EB6921
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE client
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE livrable
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE projet
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE testimony
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE "user"
        SQL);
    }
}
