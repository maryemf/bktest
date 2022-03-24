/*
 Navicat Premium Data Transfer

 Source Server         : localhost
 Source Server Type    : PostgreSQL
 Source Server Version : 140002
 Source Host           : localhost:5432
 Source Catalog        : postgres
 Source Schema         : bktest

 Target Server Type    : PostgreSQL
 Target Server Version : 140002
 File Encoding         : 65001

 Date: 24/03/2022 02:19:29
*/


-- ----------------------------
-- Sequence structure for actor_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "bktest"."actor_id_seq";
CREATE SEQUENCE "bktest"."actor_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for director_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "bktest"."director_id_seq";
CREATE SEQUENCE "bktest"."director_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for movie_actor_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "bktest"."movie_actor_id_seq";
CREATE SEQUENCE "bktest"."movie_actor_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for movie_director_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "bktest"."movie_director_id_seq";
CREATE SEQUENCE "bktest"."movie_director_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 2147483647
START 1
CACHE 1;

-- ----------------------------
-- Sequence structure for movie_id_seq
-- ----------------------------
DROP SEQUENCE IF EXISTS "bktest"."movie_id_seq";
CREATE SEQUENCE "bktest"."movie_id_seq" 
INCREMENT 1
MINVALUE  1
MAXVALUE 9223372036854775807
START 1
CACHE 1;

-- ----------------------------
-- Table structure for actor
-- ----------------------------
DROP TABLE IF EXISTS "bktest"."actor";
CREATE TABLE "bktest"."actor" (
  "name" varchar(255) COLLATE "pg_catalog"."default" NOT NULL,
  "birth_date" date,
  "death_date" date,
  "birth_place" varchar(255) COLLATE "pg_catalog"."default",
  "id" int4 NOT NULL DEFAULT nextval('"bktest".actor_id_seq'::regclass)
)
;

-- ----------------------------
-- Table structure for director
-- ----------------------------
DROP TABLE IF EXISTS "bktest"."director";
CREATE TABLE "bktest"."director" (
  "name" varchar(255) COLLATE "pg_catalog"."default",
  "birth_date" date,
  "id" int4 NOT NULL DEFAULT nextval('"bktest".director_id_seq'::regclass)
)
;

-- ----------------------------
-- Table structure for movie
-- ----------------------------
DROP TABLE IF EXISTS "bktest"."movie";
CREATE TABLE "bktest"."movie" (
  "id" int4 NOT NULL,
  "title" varchar(255) COLLATE "pg_catalog"."default",
  "date_published" date,
  "genre" varchar(255) COLLATE "pg_catalog"."default",
  "duration" int4,
  "production_company" varchar(255) COLLATE "pg_catalog"."default"
)
;

-- ----------------------------
-- Table structure for movie_actor
-- ----------------------------
DROP TABLE IF EXISTS "bktest"."movie_actor";
CREATE TABLE "bktest"."movie_actor" (
  "movie_id" int4 NOT NULL,
  "actor_id" int4 NOT NULL,
  "id" int4 NOT NULL DEFAULT nextval('"bktest".movie_actor_id_seq'::regclass)
)
;

-- ----------------------------
-- Table structure for movie_director
-- ----------------------------
DROP TABLE IF EXISTS "bktest"."movie_director";
CREATE TABLE "bktest"."movie_director" (
  "movie_id" int4,
  "director_id" int4,
  "id" int4 NOT NULL DEFAULT nextval('"bktest".movie_director_id_seq'::regclass)
)
;

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "bktest"."actor_id_seq"
OWNED BY "bktest"."actor"."id";
SELECT setval('"bktest"."actor_id_seq"', 9905, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "bktest"."director_id_seq"
OWNED BY "bktest"."director"."id";
SELECT setval('"bktest"."director_id_seq"', 814, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "bktest"."movie_actor_id_seq"
OWNED BY "bktest"."movie_actor"."id";
SELECT setval('"bktest"."movie_actor_id_seq"', 30345, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
ALTER SEQUENCE "bktest"."movie_director_id_seq"
OWNED BY "bktest"."movie_director"."id";
SELECT setval('"bktest"."movie_director_id_seq"', 3026, true);

-- ----------------------------
-- Alter sequences owned by
-- ----------------------------
SELECT setval('"bktest"."movie_id_seq"', 2731, true);

-- ----------------------------
-- Primary Key structure for table actor
-- ----------------------------
ALTER TABLE "bktest"."actor" ADD CONSTRAINT "actor_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table director
-- ----------------------------
ALTER TABLE "bktest"."director" ADD CONSTRAINT "director_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table movie
-- ----------------------------
ALTER TABLE "bktest"."movie" ADD CONSTRAINT "movies_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table movie_actor
-- ----------------------------
ALTER TABLE "bktest"."movie_actor" ADD CONSTRAINT "movie_actor_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Primary Key structure for table movie_director
-- ----------------------------
ALTER TABLE "bktest"."movie_director" ADD CONSTRAINT "movie_director_pkey" PRIMARY KEY ("id");

-- ----------------------------
-- Foreign Keys structure for table movie_actor
-- ----------------------------
ALTER TABLE "bktest"."movie_actor" ADD CONSTRAINT "ma_actor_fk" FOREIGN KEY ("actor_id") REFERENCES "bktest"."actor" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "bktest"."movie_actor" ADD CONSTRAINT "ma_movie_fk" FOREIGN KEY ("movie_id") REFERENCES "bktest"."movie" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;

-- ----------------------------
-- Foreign Keys structure for table movie_director
-- ----------------------------
ALTER TABLE "bktest"."movie_director" ADD CONSTRAINT "md_director_fk" FOREIGN KEY ("director_id") REFERENCES "bktest"."director" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
ALTER TABLE "bktest"."movie_director" ADD CONSTRAINT "md_movie_fk" FOREIGN KEY ("movie_id") REFERENCES "bktest"."movie" ("id") ON DELETE NO ACTION ON UPDATE NO ACTION;
