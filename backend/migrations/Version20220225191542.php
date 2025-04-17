<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220225191542 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE categories (id SERIAL NOT NULL, name VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE halls (id SERIAL NOT NULL, name VARCHAR(16) NOT NULL, capacity SMALLINT NOT NULL, type VARCHAR(8) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE movies (id SERIAL NOT NULL, category_id INT NOT NULL, producer_id INT NOT NULL, title VARCHAR(64) NOT NULL, description VARCHAR(65535) DEFAULT NULL, poster VARCHAR(255) DEFAULT NULL, price NUMERIC(10, 0) NOT NULL, year SMALLINT NOT NULL, duration SMALLINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX movies_category_fkey ON movies (category_id)');
        $this->addSql('CREATE INDEX movies_producer_fkey ON movies (producer_id)');
        $this->addSql('CREATE TABLE producers (id SERIAL NOT NULL, name VARCHAR(64) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE sessions (id SERIAL NOT NULL, movie_id INT NOT NULL, hall_id INT NOT NULL, begin_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX sessions_movie_fkey ON sessions (movie_id)');
        $this->addSql('CREATE INDEX sessions_hall_fkey ON sessions (hall_id)');
        $this->addSql('CREATE TABLE tickets (id SERIAL NOT NULL, user_id INT NOT NULL, session_id INT NOT NULL, place SMALLINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX tickets_user_fkey ON tickets (user_id)');
        $this->addSql('CREATE INDEX tickets_session_fkey ON tickets (session_id)');
        $this->addSql('CREATE TABLE users (id SERIAL NOT NULL, name VARCHAR(32) NOT NULL, email VARCHAR(128) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX users_email_key ON users (email)');
        $this->addSql('ALTER TABLE movies ADD CONSTRAINT movies_category_fkey FOREIGN KEY (category_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE movies ADD CONSTRAINT movies_producer_fkey FOREIGN KEY (producer_id) REFERENCES producers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sessions ADD CONSTRAINT sessions_movie_fkey FOREIGN KEY (movie_id) REFERENCES movies (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE sessions ADD CONSTRAINT sessions_hall_fkey FOREIGN KEY (hall_id) REFERENCES halls (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT tickets_user_fkey FOREIGN KEY (user_id) REFERENCES users (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE tickets ADD CONSTRAINT tickets_session_fkey FOREIGN KEY (session_id) REFERENCES sessions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE halls');
        $this->addSql('DROP TABLE movies');
        $this->addSql('DROP TABLE producers');
        $this->addSql('DROP TABLE sessions');
        $this->addSql('DROP TABLE tickets');
        $this->addSql('DROP TABLE users');
    }
}
