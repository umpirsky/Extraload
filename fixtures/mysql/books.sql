CREATE DATABASE extraload_fixtures;

USE extraload_fixtures;

GRANT SELECT, INSERT, UPDATE, DELETE, CREATE, DROP, INDEX, ALTER, LOCK TABLES, CREATE TEMPORARY TABLES
ON extraload_fixtures.* TO 'extraload_fixtures'@'localhost' IDENTIFIED BY 'password';

CREATE TABLE books (
    isbn VARCHAR(16) NOT NULL,
    title VARCHAR(128) NOT NULL,
    author VARCHAR(128) NOT NULL
);

CREATE TABLE my_books (
    isbn VARCHAR(16) NOT NULL,
    title VARCHAR(128) NOT NULL,
    author VARCHAR(128) NOT NULL
);

INSERT INTO books (isbn, title, author)
VALUES
('99921-58-10-7', 'Divine Comedy', 'Dante Alighieri'),
('9781847493583', 'La Vita Nuova', 'Dante Alighieri'),
('9971-5-0210-0', 'A Tale of Two Cities', 'Charles Dickens'),
('960-425-059-0', 'The Lord of the Rings', 'J. R. R. Tolkien'),
('80-902734-1-6', 'And Then There Were None', 'Agatha Christie');

INSERT INTO my_books (isbn, title, author)
VALUES
('9781503262140', 'Faust', 'Johann Wolfgang von Goethe'),
('978-0156949606', 'The Waves', 'Virgina Woolf');
