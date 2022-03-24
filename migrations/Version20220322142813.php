<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220322142813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP SEQUENCE bktest.actor_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE bktest.director_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE bktest.movie_actor_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE bktest.movie_director_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE bktest.movie_id_seq CASCADE');

        $this->addSql('CREATE SEQUENCE bktest.actor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE bktest.director_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE bktest.movie_actor_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE bktest.movie_director_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE bktest.movie_id_seq INCREMENT BY 1 MINVALUE 1 START 1');

        $this->addSql('DROP TABLE IF EXISTS bktest.actor');
        $this->addSql('CREATE TABLE bktest.actor (name varchar(255) NOT NULL, birth_date date, death_date date, birth_place varchar(255), id INT NOT NULL DEFAULT nextval(\'bktest.actor_id_seq\'::regclass), PRIMARY KEY(id))');
        $this->addSql('DROP TABLE IF EXISTS bktest.director');
        $this->addSql('CREATE TABLE bktest.director (name varchar(255) NOT NULL, birth_date date, id INT NOT NULL DEFAULT nextval(\'bktest.director_id_seq\'::regclass), PRIMARY KEY(id))');
        $this->addSql('DROP TABLE IF EXISTS bktest.movie');
        $this->addSql('CREATE TABLE bktest.movie (title varchar(255), date_published date, genre varchar(255), duration INT, production_company varchar(255), id INT NOT NULL DEFAULT nextval(\'bktest.director_id_seq\'::regclass), PRIMARY KEY(id))');
        $this->addSql('DROP TABLE IF EXISTS bktest.movie_actor');
        $this->addSql('CREATE TABLE movie_actor (id INT NOT NULL DEFAULT nextval(\'bktest.movie_actor_id_seq\'::regclass), movie_id INT DEFAULT NULL, actor_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('DROP TABLE IF EXISTS bktest.movie_director');
        $this->addSql('CREATE TABLE movie_director (id INT NOT NULL DEFAULT nextval(\'bktest.movie_director_id_seq\'::regclass), movie_id INT DEFAULT NULL, director_id INT DEFAULT NULL, PRIMARY KEY(id))');

        $this->addSql('ALTER TABLE bktest.actor ADD CONSTRAINT actor_pkey PRIMARY KEY (id)') ;
        $this->addSql('ALTER TABLE bktest.director ADD CONSTRAINT director_pkey PRIMARY KEY (id)') ;
        $this->addSql('ALTER TABLE bktest.movie ADD CONSTRAINT movies_pkey PRIMARY KEY (id)') ;
        $this->addSql('ALTER TABLE bktest.movie_actor ADD CONSTRAINT movie_actor_pkey PRIMARY KEY (id)') ;
        $this->addSql('ALTER TABLE bktest.movie_director ADD CONSTRAINT movie_director_pkey PRIMARY KEY (id)') ;
        
        $this->addSql('ALTER TABLE bktest.actor ADD CONSTRAINT ma_actor_fk FOREIGN KEY (actor_id) REFERENCES bktest.actor (id) ON DELETE NO ACTION ON UPDATE NO ACTION');
        $this->addSql('ALTER TABLE bktest.movie_actor ADD CONSTRAINT ma_movie_fk FOREIGN KEY (movie_id) REFERENCES bktest.movie (id) ON DELETE NO ACTION ON UPDATE NO ACTION');
        $this->addSql('ALTER TABLE bktest.movie_director ADD CONSTRAINT md_director_fk FOREIGN KEY (director_id) REFERENCES bktest.director (id) ON DELETE NO ACTION ON UPDATE NO ACTION');
        $this->addSql('ALTER TABLE bktest.movie_director ADD CONSTRAINT md_movie_fk FOREIGN KEY (movie_id) REFERENCES bktest.movie (id) ON DELETE NO ACTION ON UPDATE NO ACTION');
        
    }

    
}
