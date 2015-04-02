GRANT ALL ON solid_refactoring.* TO 'solid'@'localhost' IDENTIFIED BY 'R3f@ct0r!ng';
GRANT USAGE ON *.* TO 'solid'@'localhost' IDENTIFIED BY 'R3f@ct0r!ng';
FLUSH PRIVILEGES;

DROP DATABASE IF EXISTS solid_refactoring;
CREATE DATABASE solid_refactoring;

use solid_refactoring

CREATE TABLE users (
    id INT NOT NULL AUTO_INCREMENT,
    username VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    group_id INT NOT NULL,
    PRIMARY KEY (id),
    UNIQUE INDEX (username)
);

CREATE TABLE groups (
   id INT NOT NULL AUTO_INCREMENT,
   name VARCHAR(255) NOT NULL,
   PRIMARY KEY (id),
   UNIQUE INDEX (name)
);
 
