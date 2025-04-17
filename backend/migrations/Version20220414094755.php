<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20220414094755 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE movies DROP CONSTRAINT movies_category_fkey');
        $this->addSql('ALTER TABLE movies DROP CONSTRAINT movies_producer_fkey');
        $this->addSql('DROP SEQUENCE categories_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE producers_id_seq CASCADE');
        $this->addSql('DROP INDEX movies_category_fkey');
        $this->addSql('DROP INDEX movies_producer_fkey');
        $this->addSql('ALTER TABLE movies DROP category_id');
        $this->addSql('ALTER TABLE movies DROP producer_id');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE producers');

        $this->addSql('CREATE TABLE countries (id SERIAL NOT NULL, name VARCHAR(64) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE country_movie (country_id INT NOT NULL, movie_id INT NOT NULL, PRIMARY KEY(country_id, movie_id))');
        $this->addSql('CREATE TABLE directors (id SERIAL NOT NULL, name VARCHAR(64) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE director_movie (director_id INT NOT NULL, movie_id INT NOT NULL, PRIMARY KEY(director_id, movie_id))');
        $this->addSql('CREATE TABLE genres (id SERIAL NOT NULL, name VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE genre_movie (genre_id INT NOT NULL, movie_id INT NOT NULL, PRIMARY KEY(genre_id, movie_id))');
        $this->addSql('CREATE TABLE languages (id SERIAL NOT NULL, name VARCHAR(64) NOT NULL, code VARCHAR(2) NOT NULL, native_name VARCHAR(64) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');

        $this->addSql('ALTER TABLE movies ADD language_id INT NOT NULL');
        $this->addSql('ALTER TABLE movies ADD rating VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE movies RENAME COLUMN description TO synopsis');
        $this->addSql('ALTER TABLE movies RENAME COLUMN duration TO runtime');
        $this->addSql('ALTER TABLE halls ALTER type TYPE VARCHAR(255)');

        $this->addSql('CREATE INDEX country_movie_country_fkey ON country_movie (country_id)');
        $this->addSql('CREATE INDEX country_movie_movie_fkey ON country_movie (movie_id)');
        $this->addSql('CREATE INDEX director_movie_director_fkey ON director_movie (director_id)');
        $this->addSql('CREATE INDEX director_movie_movie_fkey ON director_movie (movie_id)');
        $this->addSql('CREATE INDEX genre_movie_genre_fkey ON genre_movie (genre_id)');
        $this->addSql('CREATE INDEX genre_movie_movie_fkey ON genre_movie (movie_id)');
        $this->addSql('CREATE INDEX movies_language_fkey ON movies (language_id)');

        $this->addSql('ALTER TABLE movies ADD CONSTRAINT movies_language_fkey FOREIGN KEY (language_id) REFERENCES languages (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE country_movie ADD CONSTRAINT country_movie_country_fkey FOREIGN KEY (country_id) REFERENCES countries (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE country_movie ADD CONSTRAINT country_movie_movie_fkey FOREIGN KEY (movie_id) REFERENCES movies (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE director_movie ADD CONSTRAINT director_movie_director_fkey FOREIGN KEY (director_id) REFERENCES directors (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE director_movie ADD CONSTRAINT director_movie_movie_fkey FOREIGN KEY (movie_id) REFERENCES movies (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE genre_movie ADD CONSTRAINT genre_movie_genre_fkey FOREIGN KEY (genre_id) REFERENCES genres (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE genre_movie ADD CONSTRAINT genre_movie_movie_fkey FOREIGN KEY (movie_id) REFERENCES movies (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE country_movie DROP CONSTRAINT country_movie_country_fkey');
        $this->addSql('ALTER TABLE director_movie DROP CONSTRAINT director_movie_director_fkey');
        $this->addSql('ALTER TABLE genre_movie DROP CONSTRAINT genre_movie_genre_fkey');
        $this->addSql('ALTER TABLE movies DROP CONSTRAINT movies_language_fkey');
        $this->addSql('CREATE SEQUENCE categories_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE producers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE categories (id SERIAL NOT NULL, name VARCHAR(32) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE producers (id SERIAL NOT NULL, name VARCHAR(64) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE countries');
        $this->addSql('DROP TABLE country_movie');
        $this->addSql('DROP TABLE directors');
        $this->addSql('DROP TABLE director_movie');
        $this->addSql('DROP TABLE genres');
        $this->addSql('DROP TABLE genre_movie');
        $this->addSql('DROP TABLE languages');
        $this->addSql('DROP INDEX IDX_C61EED3082F1BAF4');
        $this->addSql('ALTER TABLE movies ADD producer_id INT NOT NULL');
        $this->addSql('ALTER TABLE movies DROP rating');
        $this->addSql('ALTER TABLE movies RENAME COLUMN language_id TO category_id');
        $this->addSql('ALTER TABLE movies RENAME COLUMN synopsis TO description');
        $this->addSql('ALTER TABLE movies RENAME COLUMN runtime TO duration');
        $this->addSql('ALTER TABLE movies ADD CONSTRAINT movies_category_fkey FOREIGN KEY (category_id) REFERENCES categories (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE movies ADD CONSTRAINT movies_producer_fkey FOREIGN KEY (producer_id) REFERENCES producers (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX movies_producer_fkey ON movies (producer_id)');
        $this->addSql('CREATE INDEX movies_category_fkey ON movies (category_id)');
        $this->addSql('ALTER TABLE halls ALTER type TYPE VARCHAR(8)');
    }
}
