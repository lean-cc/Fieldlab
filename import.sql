DROP DATABASE IF EXISTS `fieldlab`;
CREATE DATABASE `fieldlab`;
USE `fieldlab`;

CREATE TABLE `users` (
    userId int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username varchar(25),
    email varchar(255),
    password varchar(255),
    docent tinyint,
    klas varchar(255)
);

CREATE TABLE `challenges` (
    challengeId int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name blob,
    info blob,
    date DATE,
    klas varchar(255)
);

CREATE TABLE `inschrijven` (
    inschrijvId int UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    userId int UNSIGNED,
    FOREIGN KEY (userId) REFERENCES users(userId),
    challengeId int UNSIGNED,
    FOREIGN KEY (challengeId) REFERENCES challenges(challengeId)
);