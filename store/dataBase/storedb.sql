CREATE USER 'provenusr'@'localhost' IDENTIFIED BY 'provenpass';
 
CREATE DATABASE storedb
  DEFAULT CHARACTER SET utf8
  DEFAULT COLLATE utf8_general_ci;
 
GRANT ALL PRIVILEGES ON storedb.* TO 'provenusr'@'localhost' WITH GRANT OPTION;


USE storedb;
 
CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(20) NOT NULL UNIQUE,
    password VARCHAR(40) NOT NULL,
    role VARCHAR(10) NOT NULL DEFAULT 'registered',
    name VARCHAR(40) NOT NULL, 
    surname VARCHAR(40) NOT NULL
) ENGINE InnoDb;

CREATE TABLE products (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    description VARCHAR(100) NOT NULL DEFAULT 'description example',
    price FLOAT NOT NULL,
    stock INTEGER NOT NULL DEFAULT 200
) ENGINE InnoDb;

INSERT INTO users VALUES (0, "user01", "pass01", "admin", "name01", "surname01");
INSERT INTO users VALUES (0, "user02", "pass02", "admin", "name02", "surname02");
INSERT INTO users VALUES (0, "user03", "pass03", "admin", "name03", "surname03");
INSERT INTO users VALUES (0, "user04", "pass04", "staff", "name04", "surname04");
INSERT INTO users VALUES (0, "user05", "pass05", "registered", "name05", "surname05");
INSERT INTO users VALUES (0, "user06", "pass06", "staff", "name06", "surname06");
INSERT INTO users VALUES (0, "user07", "pass07", "staff", "name07", "surname07");
INSERT INTO users VALUES (0, "user08", "pass08", "staff", "name08", "surname08");
INSERT INTO users VALUES (0, "user09", "pass09", "staff", "name09", "surname09");

INSERT INTO products VALUES (0, "desc01", 1001, 101);
INSERT INTO products VALUES (0, "desc02", 1002, 102);
INSERT INTO products VALUES (0, "desc03", 1003, 103);
INSERT INTO products VALUES (0, "desc04", 1004, 104);
INSERT INTO products VALUES (0, "desc05", 1005, 105);
INSERT INTO products VALUES (0, "desc06", 1006, 106);
INSERT INTO products VALUES (0, "desc07", 1007, 107);
INSERT INTO products VALUES (0, "desc08", 1008, 108);
INSERT INTO products VALUES (0, "desc09", 1009, 109);

