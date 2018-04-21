CREATE DATABASE extraload_fixtures;

USE extraload_fixtures;

GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, LOCK TABLES, CREATE TEMPORARY TABLES
ON extraload_fixtures.* TO 'extraload_fixtures'@'localhost' IDENTIFIED BY 'password';

CREATE TABLE books (
    isbn VARCHAR(16) NOT NULL,
    title VARCHAR(128) NOT NULL,
    author VARCHAR(128) NOT NULL
);
